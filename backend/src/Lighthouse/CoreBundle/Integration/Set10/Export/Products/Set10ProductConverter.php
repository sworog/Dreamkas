<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Export\Products;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @DI\Service("lighthouse.core.integration.set10.export.products.converter")
 */
class Set10ProductConverter
{
    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var MoneyModelTransformer
     */
    protected $moneyModelTransformer;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @DI\InjectParams({
     *     "storeProductRepository"=@DI\Inject("lighthouse.core.document.repository.store_product"),
     *     "moneyModelTransformer"=@DI\Inject("lighthouse.core.data_transformer.money_model"),
     *     "translator"=@DI\Inject("translator"),
     * })
     */
    public function __construct(
        StoreProductRepository $storeProductRepository,
        MoneyModelTransformer $moneyModelTransformer,
        TranslatorInterface $translator
    ) {
        $this->storeProductRepository = $storeProductRepository;
        $this->moneyModelTransformer = $moneyModelTransformer;
        $this->translator = $translator;
    }

    /**
     * @param Product $product
     * @return string[]
     */
    public function makeXmlByProduct(Product $product)
    {
        $xmlProducts = array();

        if (!$this->validateProduct($product)) {
            return $xmlProducts;
        }

        $storeProducts = $this->storeProductRepository->findByProduct($product);

        $versionProducts = array();

        foreach ($storeProducts as $storeProduct) {
            $priceString = (string) $storeProduct->roundedRetailPrice->getCount();
            $uniqueVersionString = $priceString;
            if (!array_key_exists($uniqueVersionString, $versionProducts)) {
                $versionProducts[$uniqueVersionString] = array(
                    'storeProductModel' => $storeProduct,
                    'storeNumbers' => array(),
                );
            }
            $versionProducts[$uniqueVersionString]['storeNumbers'][] = $storeProduct->store->number;
        }

        foreach ($versionProducts as $version) {
            $goodElement = $this->createProductXml($version['storeProductModel'], $version['storeNumbers']);
            $xmlProducts[] = $goodElement->asXmlWithoutHeader();
        }

        return $xmlProducts;
    }

    /**
     * @param StoreProduct $storeProductModel
     * @param array $storeNumbers
     * @return GoodElement
     */
    protected function createProductXml(StoreProduct $storeProductModel, array $storeNumbers)
    {
        $product = $storeProductModel->product;

        $goodElement = GoodElement::create();
        $goodElement->setMarking($product->sku);
        $goodElement->setShopIndices($storeNumbers);
        $goodElement->setName($product->name);
        $goodElement->setBarcode($product->barcode);
        $goodElement->setProductType();
        $goodElement->setPrice(
            $this->moneyModelTransformer->transform($storeProductModel->roundedRetailPrice)
        );
        $goodElement->setVat($product->vat);
        $goodElement->setGroups(
            array(
                $product->subCategory->name => $product->subCategory->name,
                $product->subCategory->category->name => $product->subCategory->category->name,
                $product->subCategory->category->group->name => $product->subCategory->category->group->name,
            )
        );
        $goodElement->setMeasureType(
            $product->units,
            $this->translator->trans('lighthouse.units.' . $product->units, array(), 'units')
        );
        $goodElement->setPluginProperty();

        return $goodElement;
    }

    /**
     * @param Product $product
     * @return bool
     */
    protected function validateProduct(Product $product)
    {
        if ($product->purchasePrice === null
            || ($product->purchasePrice instanceof Money && $product->purchasePrice->isNull())
        ) {
            return false;
        }

        switch ($product->retailPricePreference) {
            case $product::RETAIL_PRICE_PREFERENCE_MARKUP:
                if ($product->retailMarkupMin === null
                    || $product->retailMarkupMax === null
                    || $product->retailMarkupMin === ''
                    || $product->retailMarkupMax === ''
                ) {
                    return false;
                }
                break;

            case $product::RETAIL_PRICE_PREFERENCE_PRICE:
                if ($product->retailPriceMin === null
                    || $product->retailPriceMax === null
                    || $product->retailPriceMin === ''
                    || $product->retailPriceMax === ''
                    || ($product->retailPriceMin instanceof Money && $product->retailPriceMin->isNull())
                    || ($product->retailPriceMax instanceof Money && $product->retailPriceMax->isNull())
                ) {
                    return false;
                }
                break;
        }

        return true;
    }
}

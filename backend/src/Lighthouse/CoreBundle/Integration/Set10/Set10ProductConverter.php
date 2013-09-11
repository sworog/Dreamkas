<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Symfony\Component\Translation\Translator;

/**
 * @DI\Service("lighthouse.core.service.convert.set10.product")
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
        Translator $translator
    ) {
        $this->storeProductRepository = $storeProductRepository;
        $this->moneyModelTransformer = $moneyModelTransformer;
        $this->translator = $translator;
    }

    public function makeXmlByProduct(Product $product)
    {
        if (!$this->validateProduct($product)) {
            return array();
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

        $xmlProducts = array();
        foreach ($versionProducts as $version) {
            /** @var StoreProduct $storeProductModel */
            $storeProductModel = $version['storeProductModel'];
            /** @var Product $productModel */
            $productModel = $storeProductModel->product;
            $goodElement = new \SimpleXMLElement('<good></good>');
            $goodElement->addAttribute('marking-of-the-good', $productModel->sku);
            $goodElement->addChild('shop-indices', implode(" ", $version['storeNumbers']));
            $goodElement->addChild("name", $product->name);
            $barcodeElement = $goodElement->addChild('bar-code');
            $barcodeElement->addAttribute('code', $product->barcode);
            $barcodeElement->addChild('count', 1);
            $barcodeElement->addChild('default-code', 'true');
            $goodElement->addChild('product-type', 'ProductPieceEntity');
            $priceElement = $goodElement->addChild('price-entry');
            $priceElement->addAttribute(
                'price',
                $this->moneyModelTransformer->transform($storeProductModel->roundedRetailPrice)
            );
            $priceElement->addChild('number', 1);
            $goodElement->addChild('vat', $product->vat);
            $subCategoryElement = $goodElement->addChild('group');
            $subCategoryElement->addAttribute('id', $product->subCategory->name);
            $subCategoryElement->addChild('name', $product->subCategory->name);
            $categoryElement = $subCategoryElement->addChild('parent-group');
            $categoryElement->addAttribute('id', $product->subCategory->category->name);
            $categoryElement->addChild('name', $product->subCategory->category->name);
            $groupElement = $categoryElement->addChild('parent-group');
            $groupElement->addAttribute('id', $product->subCategory->category->group->name);
            $groupElement->addChild('name', $product->subCategory->category->group->name);
            $unitsElement = $goodElement->addChild('measure-type');
            $unitsElement->addAttribute('id', $product->units);
            $unitsElement->addChild(
                'name',
                $this->translator->trans('lighthouse.units.' . $product->units, array(), 'units')
            );
            $pluginElement = $goodElement->addChild('plugin-property');
            $pluginElement->addAttribute('key', 'precision');
            $pluginElement->addAttribute('value', 1);

            $xmlProducts[] = $goodElement->asXML();
        }

        return $xmlProducts;
    }

    protected function validateProduct(Product $product)
    {
        if ($product->purchasePrice == null) {
            return false;
        }

        switch ($product->retailPricePreference) {
            case $product::RETAIL_PRICE_PREFERENCE_MARKUP:
                if ($product->retailMarkupMin == null || $product->retailMarkupMax == null) {
                    return false;
                }
                break;

            case $product::RETAIL_PRICE_PREFERENCE_PRICE:
                if ($product->retailPriceMin == null || $product->retailPriceMax == null) {
                    return false;
                }
                break;
        }

        return true;
    }
}

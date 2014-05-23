<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Export\Products;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Type\AlcoholType;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Symfony\Component\Translation\TranslatorInterface;
use Lighthouse\CoreBundle\Integration\Set10\Import\Products\GoodElement;

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
     * @param bool $withoutHeader
     * @return string[]
     */
    public function makeXmlByProduct(Product $product, $withoutHeader = true)
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
        ksort($versionProducts);

        foreach ($versionProducts as $version) {
            $goodElement = $this->createProductXml($version['storeProductModel'], $version['storeNumbers']);
            $xmlProducts[] = ($withoutHeader) ? $goodElement->asXmlWithoutHeader() : $goodElement->asXml();
        }

        return $xmlProducts;
    }

    /**
     * @param StoreProduct $storeProduct
     * @param array $storeNumbers
     * @return GoodElement
     */
    protected function createProductXml(StoreProduct $storeProduct, array $storeNumbers)
    {
        $product = $storeProduct->product;

        $goodElement = GoodElement::create();
        $goodElement->setMarkingOfTheGood($product->sku);
        $goodElement->setShopIndices($storeNumbers);
        $goodElement->setGoodName($product->name);
        $this->setBarcodes($goodElement, $storeProduct);
        $goodElement->setPrice(
            $this->moneyModelTransformer->transform($storeProduct->roundedRetailPrice)
        );
        $goodElement->setVat($product->vat);
        $goodElement->setGroups(
            array(
                $product->subCategory->name => $product->subCategory->name,
                $product->subCategory->category->name => $product->subCategory->category->name,
                $product->subCategory->category->group->name => $product->subCategory->category->group->name,
            )
        );
        $this->setProductTypeProperties($goodElement, $product);

        $goodElement->setMeasureType(
            $product->getUnits(),
            $this->translator->trans('lighthouse.units.' . $product->getUnits(), array(), 'units')
        );


        return $goodElement;
    }

    /**
     * @param GoodElement $goodElement
     * @param Product $product
     */
    protected function setProductTypeProperties(GoodElement $goodElement, Product $product)
    {
        switch ($product->getType()) {
            case WeightType::TYPE:
                $this->setWeightProductTypeProperties($goodElement, $product);
                break;
            case AlcoholType::TYPE:
                $this->setAlcoholProductTypeProperties($goodElement, $product);
                break;
            case UnitType::TYPE:
                $this->setUnitProductTypeProperties($goodElement, $product);
                break;
        }
    }

    /**
     * @param GoodElement $goodElement
     * @param Product $product
     */
    protected function setWeightProductTypeProperties(GoodElement $goodElement, Product $product)
    {
        $goodElement->setProductType(GoodElement::PRODUCT_WEIGHT_ENTITY);
        $goodElement->setPluginProperty('precision', '0.001');
        if ($product->typeProperties->nameOnScales) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_NAME_ON_SCALE_SCREEN,
                $product->typeProperties->nameOnScales
            );
        }
        if ($product->typeProperties->descriptionOnScales) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_DESCRIPTION_ON_SCALE_SCREEN,
                $product->typeProperties->descriptionOnScales
            );
        }
        if ($product->typeProperties->ingredients) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_COMPOSITION,
                $product->typeProperties->ingredients
            );
        }
        if ($product->typeProperties->nutritionFacts) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_FOOD_VALUE,
                $product->typeProperties->nutritionFacts
            );
        }
        if ($product->typeProperties->shelfLife) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_GOOD_FOR_HOURS,
                $product->typeProperties->shelfLife
            );
        }
    }

    /**
     * @param GoodElement $goodElement
     * @param Product $product
     */
    protected function setUnitProductTypeProperties(GoodElement $goodElement, Product $product)
    {
        $goodElement->setProductType(GoodElement::PRODUCT_PIECE_ENTITY);
        $goodElement->setPluginProperty('precision', '0.001');
    }

    /**
     * @param GoodElement $goodElement
     * @param Product $product
     */
    protected function setAlcoholProductTypeProperties(GoodElement $goodElement, Product $product)
    {
        $goodElement->setProductType(GoodElement::PRODUCT_SPIRITS_ENTITY);
        $goodElement->setPluginProperty('precision', '0.001');
        if ($product->typeProperties->alcoholByVolume) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_ALCOHOLIC_CONTENT_PERCENTAGE,
                $product->typeProperties->alcoholByVolume->toNumber()
            );
        }
        if ($product->typeProperties->volume) {
            $goodElement->setPluginProperty(
                GoodElement::PLUGIN_PROPERTY_VOLUME,
                $product->typeProperties->volume->toNumber()
            );
        }
    }

    /**
     * @param GoodElement $goodElement
     * @param StoreProduct $storeProduct
     */
    protected function setBarcodes(GoodElement $goodElement, StoreProduct $storeProduct)
    {
        $goodElement->addBarcode($storeProduct->product->barcode, 1, true);
        foreach ($storeProduct->product->barcodes as $barcode) {
            $goodElement->addBarcode(
                $barcode->barcode,
                $barcode->quantity->toNumber(),
                false,
                $barcode->getPriceAsFloat()
            );
        }
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

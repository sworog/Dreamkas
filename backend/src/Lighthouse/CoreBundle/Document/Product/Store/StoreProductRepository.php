<?php

namespace Lighthouse\CoreBundle\Document\Product\Store;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreCollection;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Exception\InvalidArgumentException;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\DiExtraBundle\Annotation as DI;

class StoreProductRepository extends DocumentRepository
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function setProductRepository(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param StoreRepository $storeRepository
     */
    public function setStoreRepository(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    public function findByStoreProduct(Store $store, Product $product)
    {
        $id = $this->getIdByStoreAndProduct($store, $product);
        return $this->find($id);
    }

    /**
     * @param Reasonable $reason
     * @return StoreProduct
     */
    public function findOrCreateByReason(Reasonable $reason)
    {
        $store = $reason->getReasonParent()->getStore();
        $product = $reason->getReasonProduct();
        return $this->findOrCreateByStoreProduct($store, $product);
    }

    /**
     * @param StoreProduct $storeProduct
     */
    public function refresh(StoreProduct $storeProduct)
    {
        $this->uow->getDocumentPersister($this->documentName)->refresh($storeProduct->id, $storeProduct);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    public function createByStoreProduct(Store $store, Product $product)
    {
        $uow = $this->getDocumentManager()->getUnitOfWork();

        $id = $this->getIdByStoreAndProduct($store, $product);
        $storeProduct = $uow->tryGetById($id, StoreProduct::getClassName());

        if (!$storeProduct) {
            $storeProduct = new StoreProduct();
            $storeProduct->id = $id;
            $storeProduct->store = $store;
            $storeProduct->product = $product;
            $storeProduct->subCategory = $product->subCategory;

            $storeProduct->retailMarkup = $product->retailMarkupMax;
            $this->updateRetails($storeProduct);

            $uow->persist($storeProduct);
        }

        return $storeProduct;
    }

    /**
     * @param Store $store
     * @param Product $product
     * @throws InvalidArgumentException
     * @return string
     */
    public function getIdByStoreAndProduct(Store $store, Product $product)
    {
        if (null === $store->id) {
            throw new InvalidArgumentException('Empty store id');
        }
        if (null === $product->id) {
            throw new InvalidArgumentException('Empty product id');
        }
        return md5($store->id . ':' . $product->id);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    public function findOrCreateByStoreProduct(Store $store, Product $product)
    {
        $storeProduct = $this->findByStoreProduct($store, $product);
        if (null === $storeProduct) {
            $storeProduct = $this->createByStoreProduct($store, $product);
        }
        return $storeProduct;
    }

    /**
     * @param SubCategory $subCategory
     * @param Store $store
     * @return StoreProductCollection
     */
    public function findByStoreSubCategory(Store $store, SubCategory $subCategory)
    {
        $productCollection = $this->productRepository->findBySubCategory($subCategory);
        $products = array();
        foreach ($productCollection as $product) {
            $products[$product->id] = $product;
        }

        $cursor = $this->findBy(
            array(
                'subCategory' => $subCategory->id,
                'store' => $store->id
            )
        );

        foreach ($cursor as $storeProduct) {
            if (isset($products[$storeProduct->product->id])) {
                $products[$storeProduct->product->id] = $storeProduct;
            }
        }

        foreach ($products as $productId => $product) {
            if ($product instanceof Product) {
                $products[$productId] = $this->createByStoreProduct($store, $product);
            }
        }

        return new StoreProductCollection(array_values($products));
    }

    /**
     * @param Product $product
     * @return StoreProduct[]|StoreProductCollection
     */
    public function findByProduct(Product $product)
    {
        /* @var StoreProduct[]|StoreProductCollection $storeProducts */
        $cursor = $this->findBy(array('product' => $product->id));
        $storeProducts = new StoreProductCollection($cursor);

        /* @var Store[]|StoreCollection $stores */
        $stores = new StoreCollection($this->storeRepository->findAll());

        // filter found stores
        foreach ($storeProducts as $storeProduct) {
            $stores->removeElement($storeProduct->store);
        }

        foreach ($stores as $store) {
            $storeProduct = $this->createByStoreProduct($store, $product);
            $storeProducts->add($storeProduct);
        }

        return $storeProducts;
    }

    /**
     * @param Store $store
     * @return StoreProductCollection
     */
    public function findByStore(Store $store)
    {
        $productCollection = $this->productRepository->findAll();
        $products = array();
        foreach ($productCollection as $product) {
            $products[$product->id] = $product;
        }

        $cursor = $this->findBy(array('store' => $store->id));

        foreach ($cursor as $storeProduct) {
            if (isset($products[$storeProduct->product->id])) {
                $products[$storeProduct->product->id] = $storeProduct;
            }
        }

        foreach ($products as $productId => $product) {
            if ($product instanceof Product) {
                $products[$productId] = $this->createByStoreProduct($store, $product);
            }
        }

        return new StoreProductCollection(array_values($products));
    }

    /**
     * @param StoreProduct $storeProduct
     * @param Product $product
     */
    public function updateRetailPriceByProduct(StoreProduct $storeProduct, Product $product)
    {
        switch ($storeProduct->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_MARKUP:
                if (null === $storeProduct->retailMarkup) {
                    $storeProduct->retailMarkup = $product->retailMarkupMax;
                } elseif ($storeProduct->retailMarkup < $product->retailMarkupMin) {
                    $storeProduct->retailMarkup = $product->retailMarkupMin;
                } elseif ($storeProduct->retailMarkup > $product->retailMarkupMax) {
                    $storeProduct->retailMarkup = $product->retailMarkupMax;
                }
                break;
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                if ($storeProduct->retailPrice->isNull()) {
                    $storeProduct->retailPrice = $product->retailPriceMax;
                } elseif ($storeProduct->retailPrice->getCount() < $product->retailPriceMin->getCount()) {
                    $storeProduct->retailPrice = $product->retailPriceMin;
                } elseif ($storeProduct->retailPrice->getCount() > $product->retailPriceMax->getCount()) {
                    $storeProduct->retailPrice = $product->retailPriceMax;
                }
                break;
        }
        $this->updateRetails($storeProduct, $product);
    }

    /**
     * @param StoreProduct $storeProduct
     * @param Product $product
     */
    public function updateRetails(StoreProduct $storeProduct, Product $product = null)
    {
        $product = ($product) ?: $storeProduct->product;
        switch ($storeProduct->retailPricePreference) {
            case Product::RETAIL_PRICE_PREFERENCE_PRICE:
                $storeProduct->retailMarkup = $this->calcMarkup(
                    $storeProduct->retailPrice,
                    $product->purchasePrice
                );
                break;
            case Product::RETAIL_PRICE_PREFERENCE_MARKUP:
            default:
                $storeProduct->retailPrice = $this->calcRetailPrice(
                    $storeProduct->retailMarkup,
                    $product->purchasePrice
                );
                $storeProduct->retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;
                break;
        }
        if (null !== $storeProduct->retailPrice && !$storeProduct->retailPrice->isNull()) {
            $storeProduct->roundedRetailPrice = $product->rounding->round($storeProduct->retailPrice);
        }
    }

    /**
     * @param Money $retailPrice
     * @param Money $purchasePrice
     * @return float|null
     */
    protected function calcMarkup(Money $retailPrice = null, Money $purchasePrice = null)
    {
        $roundedMarkup = null;
        if (null !== $retailPrice && !$retailPrice->isNull() && null !== $purchasePrice) {
            $markup = (($retailPrice->getCount() / $purchasePrice->getCount()) * 100) - 100;
            $roundedMarkup = Decimal::createFromNumeric($markup, 2)->toNumber();
        }
        return $roundedMarkup;
    }

    /**
     * @param float $retailMarkup
     * @param Money $purchasePrice
     * @return Money
     */
    protected function calcRetailPrice($retailMarkup, Money $purchasePrice = null)
    {
        if (null !== $retailMarkup && '' !== $retailMarkup && null !== $purchasePrice) {
            $percent = 1 + ($retailMarkup / 100);
            $retailPrice = $purchasePrice->mul($percent);
        } else {
            $retailPrice = new Money();
        }
        return $retailPrice;
    }


    /**
     * @param StoreProduct $storeProduct
     * @param Money $purchasePrice
     */
    public function updateLastPurchasePrice(StoreProduct $storeProduct, Money $purchasePrice = null)
    {
        $lastPurchasePrice = (null !== $purchasePrice) ? $purchasePrice->getCount() : null;
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->returnNew(true)
            ->field('id')->equals($storeProduct->id)
            ->field('lastPurchasePrice')->set($lastPurchasePrice, true);

        $query->getQuery()->execute();
    }

    /**
     * @param string $storeProductId
     * @param int $averagePurchasePrice
     */
    public function updateAveragePurchasePrice($storeProductId, $averagePurchasePrice)
    {
        $roundedAveragePurchasePrice = Decimal::createFromNumeric($averagePurchasePrice, 0)->toNumber();

        $query = $this
            ->createQueryBuilder()
            ->update()
            ->field('id')->equals($storeProductId)
            ->field('averagePurchasePrice')->set($roundedAveragePurchasePrice, true)
            ->field('averagePurchasePriceNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }

    public function setAllAveragePurchasePriceToNotCalculate()
    {
        $this->setFieldToNotCalculate('averagePurchasePrice', null);
    }

    public function resetAveragePurchasePriceNotCalculate()
    {
        $this->resetFieldNotCalculate('averagePurchasePrice', null);
    }

    /**
     * @param string $storeProductId
     * @param float  $averageDailySales
     */
    public function updateAverageDailySales($storeProductId, $averageDailySales = null)
    {
        $query = $this
            ->createQueryBuilder()
            ->update()
            ->field('id')->equals($storeProductId)
            ->field('averageDailySales')->set($averageDailySales, true)
            ->field('inventoryRatioNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    public function setFieldToNotCalculate($field, $value = null)
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field($field)->notEqual($value)
            ->field("{$field}NotCalculate")->set(true, true);

        $query->getQuery()->execute();
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    public function resetFieldNotCalculate($field, $value = null)
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field("{$field}NotCalculate")->equals(true)
            ->field($field)->set($value, true)
            ->field("{$field}NotCalculate")->unsetField();

        $query->getQuery()->execute();
    }
}

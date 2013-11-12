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
use Lighthouse\CoreBundle\Service\RoundService;
use Lighthouse\CoreBundle\Types\Money;
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
     * @param string $storeId
     * @param string $productId
     * @return StoreProduct
     */
    public function findByStoreIdProductId($storeId, $productId)
    {
        return $this->findOneBy(array('store' => $storeId, 'product' => $productId));
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
     * @return string
     */
    public function getIdByStoreAndProduct(Store $store, Product $product)
    {
        return md5($store->id . ':' . $product->id);
    }

    /**
     * @param Store $store
     * @param Product $product
     * @return StoreProduct
     */
    public function findOrCreateByStoreProduct(Store $store, Product $product)
    {
        $storeProduct = $this->findByStoreIdProductId($store->id, $product->id);
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
            $roundedMarkup = RoundService::round($markup, 2);
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
        $retailPrice = new Money();
        if (null !== $retailMarkup && '' !== $retailMarkup && null !== $purchasePrice) {
            $percent = 1 + ($retailMarkup / 100);
            $retailPrice->setCountByQuantity($purchasePrice, $percent, true);
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
        $roundedAveragePurchasePrice = RoundService::round($averagePurchasePrice);

        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($storeProductId)
            ->field('averagePurchasePrice')->set($roundedAveragePurchasePrice, true)
            ->field('averagePurchasePriceNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }

    public function setAllAveragePurchasePriceToNotCalculate()
    {
        $this->setFieldToNotCalculate('averagePurchasePrice');
    }

    public function resetAveragePurchasePriceNotCalculate()
    {
        $this->resetFieldNotCalculate('averagePurchasePrice');
    }

    /**
     * @param string $storeProductId
     * @param float  $inventoryRatio
     */
    public function updateInventoryRatio($storeProductId, $inventoryRatio)
    {
        $inventoryDays = (null === $inventoryRatio) ? null : 1 / $inventoryRatio;
        $query = $this
            ->createQueryBuilder()
            ->findAndUpdate()
            ->field('id')->equals($storeProductId)
            ->field('inventoryRatio')->set($inventoryRatio, true)
            ->field('inventoryDays')->set($inventoryDays, true)
            ->field('inventoryRatioNotCalculate')->unsetField();

        $query->getQuery()->execute();
    }

    /**
     * @param string $field
     */
    public function setFieldToNotCalculate($field)
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field($field)->notEqual(null)
            ->field("{$field}NotCalculate")->set(true, true);

        $query->getQuery()->execute();
    }

    /**
     * @param string $field
     */
    public function resetFieldNotCalculate($field)
    {
        $query = $this->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field("{$field}NotCalculate")->equals(true)
            ->field($field)->set(null, true)
            ->field("{$field}NotCalculate")->unsetField();

        $query->getQuery()->execute();
    }
}

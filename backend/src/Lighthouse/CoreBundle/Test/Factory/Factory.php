<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Receipt\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\Invoice\InvoiceFactory;
use Lighthouse\CoreBundle\Test\Factory\Order\OrderFactory;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Lighthouse\CoreBundle\Versionable\VersionRepository;

class Factory extends ContainerAwareFactory
{
    /**
     * @var OAuthFactory
     */
    protected $oauth;

    /**
     * @var UserFactory
     */
    protected $user;

    /**
     * @var StoreFactory
     */
    protected $store;

    /**
     * @var CatalogFactory
     */
    protected $catalog;

    /**
     * @var InvoiceFactory
     */
    protected $invoice;

    /**
     * @var OrderFactory
     */
    protected $order;

    /**
     * @var SupplierFactory
     */
    protected $supplier;

    /**
     * @var array
     */
    protected $storeProducts = array();

    /**
     * @return OAuthFactory
     */
    public function oauth()
    {
        if (null === $this->oauth) {
            $this->oauth = new OAuthFactory($this->container, $this);
        }
        return $this->oauth;
    }

    /**
     * @return UserFactory
     */
    public function user()
    {
        if (null === $this->user) {
            $this->user = new UserFactory($this->container, $this);
        }
        return $this->user;
    }

    /**
     * @return StoreFactory
     */
    public function store()
    {
        if (null === $this->store) {
            $this->store = new StoreFactory($this->container, $this);
        }
        return $this->store;
    }

    /**
     * @return CatalogFactory
     */
    public function catalog()
    {
        if (null === $this->catalog) {
            $this->catalog = new CatalogFactory($this->container, $this);
        }
        return $this->catalog;
    }

    /**
     * @return InvoiceFactory
     */
    public function invoice()
    {
        if (null === $this->invoice) {
            $this->invoice = new InvoiceFactory($this->container, $this);
        }
        return $this->invoice;
    }

    /**
     * @return OrderFactory
     */
    public function order()
    {
        if (null === $this->order) {
            $this->order = new OrderFactory($this->container, $this);
        }
        return $this->order;
    }

    /**
     * @return SupplierFactory
     */
    public function supplier()
    {
        if (null === $this->supplier) {
            $this->supplier = new SupplierFactory($this->container, $this);
        }
        return $this->supplier;
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @return string
     */
    public function getStoreProduct($storeId, $productId)
    {
        if (!isset($this->storeProducts[$storeId]) || !isset($this->storeProducts[$storeId][$productId])) {
            $this->storeProducts[$storeId][$productId] = $this
                ->getStoreProductRepository()
                ->findOrCreateByStoreIdProductId($storeId, $productId)
                ->id;
        }

        return $this->storeProducts[$storeId][$productId];
    }

    /**
     * @param array $sales
     * @return Sale[]
     */
    public function createSales(array $sales)
    {
        $self = $this;
        $saleModels = array_map(
            function ($sale) use ($self) {
                $saleModel = $self->createSale($sale['storeId'], $sale['createdDate'], $sale['sumTotal']);
                array_map(
                    function ($position) use ($self, $saleModel) {
                        $self->createSaleProduct(
                            $position['price'],
                            $position['quantity'],
                            $position['productId'],
                            $saleModel
                        );
                    },
                    $sale['positions']
                );
                return $saleModel;
            },
            $sales
        );

        $this->flush();

        return $saleModels;
    }

    /**
     * @return Factory
     */
    public function flush()
    {
        $this->getDocumentManager()->flush();
        return $this;
    }

    /**
     * @return Factory
     */
    public function clear()
    {
        $this->getDocumentManager()->clear();
        return $this;
    }

    /**
     * @param string $storeId
     * @param string $createdDate
     * @param string $sumTotal
     * @return Sale
     */
    public function createSale($storeId, $createdDate, $sumTotal)
    {
        $saleModel = new Sale();
        $saleModel->createdDate = new DateTimestamp($createdDate);
        $saleModel->store = $this->getDocumentManager()->getReference(Store::getClassName(), $storeId);
        $saleModel->hash = md5(rand() . $storeId);
        $saleModel->sumTotal = $this->getNumericFactory()->createMoney($sumTotal);

        $this->getDocumentManager()->persist($saleModel);

        return $saleModel;
    }

    /**
     * @param Sale $sale
     */
    public function deleteSale(Sale $sale)
    {
        $this->getReceiptRepository()->rollbackByHash($sale->hash);
    }

    /**
     * @param Money|string $price
     * @param Decimal|float $quantity
     * @param string $productId
     * @param Sale $sale
     * @return SaleProduct
     */
    public function createSaleProduct($price, $quantity, $productId, Sale $sale = null)
    {
        $saleProduct = new SaleProduct();
        $saleProduct->price = $this->getNumericFactory()->createMoney($price);
        $saleProduct->quantity = $this->getNumericFactory()->createQuantity($quantity);
        $saleProduct->product = $this->createProductVersion($productId);
        if ($sale) {
            $saleProduct->sale = $sale;
            $sale->products->add($saleProduct);
        }

        $this->getDocumentManager()->persist($saleProduct);

        return $saleProduct;
    }

    /**
     * @param string $productId
     * @return ProductVersion
     */
    public function createProductVersion($productId)
    {
        return $this->getProductVersionRepository()->findOrCreateByDocumentId($productId);
    }

    /**
     * @return NumericFactory
     */
    public function getNumericFactory()
    {
        return $this->container->get('lighthouse.core.types.numeric.factory');
    }

    /**
     * @return VersionRepository
     */
    protected function getProductVersionRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.product_version');
    }

    /**
     * @return StoreProductRepository
     */
    protected function getStoreProductRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.store_product');
    }

    /**
     * @return ReceiptRepository
     */
    protected function getReceiptRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.receipt');
    }

    /**
     * @return ExceptionalValidator
     */
    public function getValidator()
    {
        return $this->container->get('lighthouse.core.validator');
    }

    /**
     * @param string $name
     * @param string $url
     * @param int $size
     * @return File
     */
    public function createFile($name = 'default.txt', $url = 'http://cdn.lighthouse.pro/123', $size = 123)
    {
        $file = new File();
        $file->name = $name;
        $file->url = $url;
        $file->size = $size;
        $this->getDocumentManager()->persist($file);

        return $file;
    }
}

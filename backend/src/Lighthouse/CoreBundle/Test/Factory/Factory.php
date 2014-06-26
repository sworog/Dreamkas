<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\Organization\Organization;
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
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @method OAuthFactory oauth()
 * @method UserFactory user()
 * @method StoreFactory store()
 * @method CatalogFactory catalog()
 * @method InvoiceFactory invoice()
 * @method OrderFactory order()
 * @method SupplierFactory supplier()
 * @method OrganizationFactory organization()
 */
class Factory extends ContainerAwareFactory
{
    /**
     * @var array|string[]
     */
    protected $factoryClasses = array();

    /**
     * @var AbstractFactory[]
     */
    protected $factories;

    /**
     * @var array
     */
    protected $storeProducts = array();

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->initFactories();
        parent::__construct($container);
    }

    protected function initFactories()
    {
        $this->factories = array(
            'oauth' => OAuthFactory::getClassName(),
            'user' => UserFactory::getClassName(),
            'store' => StoreFactory::getClassName(),
            'catalog' => CatalogFactory::getClassName(),
            'invoice' => InvoiceFactory::getClassName(),
            'order' => OrderFactory::getClassName(),
            'supplier' => SupplierFactory::getClassName(),
            'organization' => Organization::getClassName(),
        );
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        if ($container) {
            $this->user()->authProject();
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return AbstractFactory
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->factories[$name])) {
            if (isset($this->factoryClasses[$name])) {
                $this->factories[$name] = new $this->factoryClasses[$name]($this->container, $this);
            } else {
                throw new \RuntimeException(sprintf('Invalid factory name: %s', $name));
            }
        }
        return $this->factories[$name];
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
}

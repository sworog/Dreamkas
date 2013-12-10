<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Sales;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Returne\Product\ReturnProduct;
use Lighthouse\CoreBundle\Document\Returne\Returne;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\ValidatorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use DateTime;

/**
 * @DI\Service("lighthouse.core.integration.set10.import.sales.importer")
 */
class SalesImporter
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
     * @var VersionRepository
     */
    protected $productVersionRepository;

    /**
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @var MoneyModelTransformer
     */
    protected $moneyTransformer;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var int
     */
    protected $batchSize = 1000;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var array
     */
    protected $stores = array();

    /**
     * @DI\InjectParams({
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "productVersionRepository" = @DI\Inject("lighthouse.core.document.repository.product_version"),
     *      "validator" = @DI\Inject("lighthouse.core.validator"),
     *      "moneyTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param ProductRepository $productRepository
     * @param StoreRepository $storeRepository
     * @param VersionRepository $productVersionRepository
     * @param ValidatorInterface $validator
     * @param MoneyModelTransformer $moneyTransformer
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        StoreRepository $storeRepository,
        VersionRepository $productVersionRepository,
        ValidatorInterface $validator,
        MoneyModelTransformer $moneyTransformer,
        NumericFactory $numericFactory
    ) {
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->productVersionRepository = $productVersionRepository;
        $this->validator = $validator;
        $this->moneyTransformer = $moneyTransformer;
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param SalesXmlParser $parser
     * @param OutputInterface $output
     * @param int $batchSize
     * @param DatePeriod $datePeriod
     * @param DotHelper $dotHelper
     * @param Stopwatch $stopwatch
     */
    public function import(
        SalesXmlParser $parser,
        OutputInterface $output,
        $batchSize = null,
        DatePeriod $datePeriod = null,
        DotHelper $dotHelper = null,
        Stopwatch $stopwatch = null
    ) {
        $this->errors = array();
        $dotHelper = ($dotHelper) ?: new DotHelper($output);
        $stopwatch = ($stopwatch) ?: new Stopwatch();
        $count = 0;
        $batchSize = ($batchSize) ?: $this->batchSize;
        $dm = $this->productRepository->getDocumentManager();

        /* @var PurchaseElement $purchaseElement */
        while ($purchaseElement = $parser->readNextElement()) {
            $count++;
            try {
                $receipt = $this->createReceipt($purchaseElement, $datePeriod);
                if (!$receipt) {
                    $dotHelper->writeError('S');
                } else {
                    $this->validator->validate($receipt, null, true, true);
                    $dm->persist($receipt);
                    $dotHelper->writeInfo('.');
                    if (0 == $count % $batchSize) {
                        $this->flush($dm, $output, $dotHelper, $stopwatch);
                    }
                }
            } catch (ValidationFailedException $e) {
                $dotHelper->writeError('V');
                $this->errors[] = array(
                    'count' => $count,
                    'exception' => $e
                );
            } catch (\Exception $e) {
                $dotHelper->writeError('E');
                $this->errors[] = array(
                    'count' => $count,
                    'exception' => $e
                );
            }
        }
        $this->flush($dm, $output, $dotHelper, $stopwatch);

        $this->outputErrors($output, $this->errors);

        $output->writeln('');
    }

    /**
     * @param DocumentManager $dm
     * @param OutputInterface $output
     * @param DotHelper $dotHelper
     * @param Stopwatch $stopwatch
     */
    protected function flush(DocumentManager $dm, OutputInterface $output, DotHelper $dotHelper, Stopwatch $stopwatch)
    {
        $e = $stopwatch->start('flush');

        $dm->flush();
        $dm->clear();

        $e->stop();

        $dotHelper->end();
        $output->writeln(sprintf('<info>Flushing</info> %d ms, %s bytes', $e->getDuration(), $e->getMemory()));
    }

    /**
     * @param OutputInterface $output
     * @param array $errors
     */
    protected function outputErrors(OutputInterface $output, array $errors)
    {
        if (count($errors) > 0) {
            $output->writeln('');
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(
                    sprintf(
                        '<comment>Sale #%d</comment> - %s',
                        $error['count'] - 1,
                        $error['exception']->getMessage()
                    )
                );
            }
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @param DatePeriod $datePeriod
     * @return Sale|Returne|null
     */
    public function createReceipt(PurchaseElement $purchaseElement, DatePeriod $datePeriod = null)
    {
        if (true === $purchaseElement->getOperationType()) {
            return $this->createSale($purchaseElement, $datePeriod);
        } elseif (false === $purchaseElement->getOperationType()) {
            return $this->createReturn($purchaseElement, $datePeriod);
        } else {
            return null;
        }
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @param DatePeriod $datePeriod
     * @return Sale
     */
    public function createSale(PurchaseElement $purchaseElement, DatePeriod $datePeriod = null)
    {
        $sale = new Sale();
        $sale->createdDate = $this->getReceiptDate($purchaseElement, $datePeriod);
        $sale->store = $this->getStore($purchaseElement->getShop());
        $sale->hash = $this->createReceiptHash($purchaseElement);
        foreach ($purchaseElement->getPositions() as $positionElement) {
            $sale->products->add($this->createSaleProduct($positionElement));
        }
        $sale->itemsCount = count($sale->products);
        $sale->sumTotal = $this->transformPrice($purchaseElement->getAmount());
        return $sale;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @param DatePeriod $datePeriod
     * @return Returne
     */
    public function createReturn(PurchaseElement $purchaseElement, DatePeriod $datePeriod = null)
    {
        $return = new Returne();
        $return->createdDate = $this->getReceiptDate($purchaseElement, $datePeriod);
        $return->store = $this->getStore($purchaseElement->getShop());
        $return->hash = $this->createReceiptHash($purchaseElement);
        foreach ($purchaseElement->getPositions() as $positionElement) {
            $return->products->add($this->createReturnProduct($positionElement));
        }
        $return->itemsCount = count($return->products);
        $return->sumTotal = $this->transformPrice($purchaseElement->getAmount());
        return $return;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @param DatePeriod $datePeriod
     * @return DateTime
     */
    protected function getReceiptDate(PurchaseElement $purchaseElement, DatePeriod $datePeriod = null)
    {
        $purchaseDateTime = $purchaseElement->getSaleDateTime();
        if ($datePeriod) {
            $purchaseDateTime->add($datePeriod->diff());
        }
        return $purchaseDateTime;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @return string
     */
    protected function createReceiptHash(PurchaseElement $purchaseElement)
    {
        $attributes = array();
        /* @var \SimpleXMLElement $attr */
        foreach ($purchaseElement->attributes() as $attr) {
            $attributes[$attr->getName()] = (string) $attr;
        }
        ksort($attributes);
        $hashStr = '';
        foreach ($attributes as $name => $value) {
            $hashStr.= sprintf('%s:%s;', $name, $value);
        }
        return md5($hashStr);
    }

    /**
     * @param PositionElement $positionElement
     * @return SaleProduct
     */
    public function createSaleProduct(PositionElement $positionElement)
    {
        $saleProduct = new SaleProduct();
        $saleProduct->quantity = $this->createQuantity($positionElement->getCount());
        $saleProduct->price = $this->transformPrice($positionElement->getCostWithDiscount());
        $saleProduct->product = $this->getProductVersion($positionElement->getGoodsCode());
        return $saleProduct;
    }

    /**
     * @param PositionElement $positionElement
     * @return ReturnProduct
     */
    public function createReturnProduct(PositionElement $positionElement)
    {
        $returnProduct = new ReturnProduct();
        $returnProduct->quantity = $this->createQuantity($positionElement->getCount());
        $returnProduct->price = $this->transformPrice($positionElement->getCostWithDiscount());
        $returnProduct->product = $this->getProductVersion($positionElement->getGoodsCode());
        return $returnProduct;
    }

    /**
     * @param string $price
     * @return Money
     */
    protected function transformPrice($price)
    {
        return $this->moneyTransformer->reverseTransform($price);
    }

    /**
     * @param string $sku
     * @return ProductVersion
     */
    protected function getProductVersion($sku)
    {
        $product = $this->getProduct($sku);
        return $this->productVersionRepository->findOrCreateByDocument($product);
    }

    /**
     * @param string $sku
     * @throws RuntimeException
     * @return Product
     */
    protected function getProduct($sku)
    {
        $product = $this->productRepository->findOneBy(array('sku' => $sku));
        if (!$product) {
            throw new RuntimeException(sprintf('Product with sku "%s" not found', $sku));
        }
        return $product;
    }

    /**
     * @param string $storeNumber
     * @return Store
     * @throws RuntimeException
     */
    public function getStore($storeNumber)
    {
        if (isset($this->stores[$storeNumber])) {
            $storeId = $this->stores[$storeNumber];
            if (false === $storeId) {
                $store = null;
            } else {
                $store = $this->storeRepository->getReference($storeId);
            }
        } else {
            $store = $this->storeRepository->findOneBy(array('number' => $storeNumber));
            if (null === $store) {
                $this->stores[$storeNumber] = false;
            } else {
                $this->stores[$storeNumber] = $store->id;
            }
        }

        if (!$store) {
            throw new RuntimeException(sprintf('Store with number "%s" not found', $storeNumber));
        }
        return $store;
    }

    /**
     * @param string $count
     * @return Quantity
     */
    protected function createQuantity($count)
    {
        return $this->numericFactory->createQuantity($count);
    }
}

<?php

namespace Lighthouse\IntegrationBundle\OneC\Import\Invoices;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Types\Date\DatePeriod;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use SplFileObject;
use DateTime;

/**
 * @DI\Service("lighthouse.integration.onec.import.invoices.importer")
 */
class InvoicesImporter
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var VersionFactory
     */
    protected $versionFactory;

    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @var ExceptionalValidator
     */
    protected $validator;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var Store[]
     */
    protected $stores = array();

    /**
     * @var Store
     */
    protected $lastStore;

    /**
     * @DI\InjectParams({
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "versionFactory" = @DI\Inject("lighthouse.core.versionable.factory"),
     *      "validator" = @DI\Inject("lighthouse.core.validator"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param StoreRepository $storeRepository
     * @param ProductRepository $productRepository
     * @param VersionFactory $versionFactory
     * @param ExceptionalValidator $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        StoreRepository $storeRepository,
        ProductRepository $productRepository,
        VersionFactory $versionFactory,
        ExceptionalValidator $validator,
        NumericFactory $numericFactory
    ) {
        $this->storeRepository = $storeRepository;
        $this->productRepository = $productRepository;
        $this->versionFactory = $versionFactory;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
        $this->documentManager = $productRepository->getDocumentManager();
    }

    /**
     * @param string $fileName
     * @param int $batchSize
     * @param OutputInterface $output
     * @param DatePeriod $datePeriod
     */
    public function import($fileName, $batchSize = 100, OutputInterface $output = null, DatePeriod $datePeriod = null)
    {
        $output = ($output) ?: new NullOutput();
        $dotHelper = new DotHelper($output);
        $file = new SplFileObject($fileName);
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl(',');

        $i = 0;
        $currentInvoice = null;
        /* @var \Exception[] $errors */
        $errors = array();
        foreach ($file as $row) {
            $invoice = null;
            try {
                $invoice = $this->createInvoice($row);
                if ($invoice) {
                    $dotHelper->end(false);
                    $output->writeln('');
                    if (++$i % $batchSize == 0) {
                        $output->writeln('<info>Flushing</info>');
                        $this->documentManager->flush();
                    }

                    $output->writeln(sprintf('Invoice <comment>%s</comment>', $invoice->number));
                    if ($datePeriod) {
                        $originalDate = clone $invoice->date;
                        $invoice->date->add($datePeriod->diff());
                        $output->writeln(
                            sprintf(
                                'Original date <comment>%s</comment> will be transformed to <comment>%s</comment>',
                                $originalDate->format(DateTime::ISO8601),
                                $invoice->date->format(DateTime::ISO8601)
                            )
                        );
                    }
                    if ($currentInvoice) {
                        $this->documentManager->persist($currentInvoice);
                    }
                    $currentInvoice = $invoice;
                    $this->validator->validate($currentInvoice);
                }
            } catch (\Exception $e) {
                $dotHelper->writeError('E');
                $errors[] = $e;
                $currentInvoice = null;
            }
            if ($currentInvoice && null === $invoice) {
                try {
                    $invoiceProduct = $this->createInvoiceProduct($row, $currentInvoice);
                    if ($invoiceProduct) {
                        $this->validator->validate($invoiceProduct);
                        $currentInvoice->products->add($invoiceProduct);
                        $dotHelper->write();
                    }
                } catch (\Exception $e) {
                    $dotHelper->writeError('E');
                    $errors[] = $e;
                    $currentInvoice = null;
                }
            }
        }
        if ($currentInvoice) {
            $this->documentManager->persist($currentInvoice);
        }
        $output->writeln('');
        $output->writeln('<info>Flushing</info>');
        $this->documentManager->flush();
        $output->writeln('');

        $this->outputErrors($errors, $output);
    }

    /**
     * @param array|\Exception[] $errors
     * @param OutputInterface $output
     */
    protected function outputErrors(array $errors, OutputInterface $output)
    {
        if (count($errors) > 0) {
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(sprintf('<error>[%s] %s</error>', get_class($error), $error->getMessage()));
            }
        }
    }

    /**
     * @param array $row
     * @return Invoice|null
     */
    protected function createInvoice(array $row)
    {
        $invoice = null;
        $matches = null;
        if (preg_match('/^Поступление\s+товаров\s+\S+/u', $row[0], $matches)) {
            $number = $row[7];
            $date = DateTime::createFromFormat('d.m.Y H:i:s', trim($row[6]));
            $storeName = trim($row[5]);
            if ($storeName) {
                $store = $this->getStore($storeName);
            } else {
                $store = $this->lastStore;
            }

            $invoice = new Invoice();
            $invoice->date = $date;
            $invoice->number = $number;
            $invoice->store = $store;
            $invoice->accepter = 'Накладных Импорт';
            $invoice->legalEntity = 'Магазин';

            // FIXME Supplier should be auto generated on import?
            //$supplier = trim($row[8]);
            //$supplier = ($supplier) ?: 'Не указан';
            //$invoice->supplier = $supplier;
        } else {
            $this->checkStoreRow($row);
        }
        return $invoice;
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function checkStoreRow(array $row)
    {
        $filteredRow = array_filter($row);
        if (count($filteredRow) == 1) {
            $storeName = reset($filteredRow);
            $store = $this->getStore($storeName, false);
            if ($store) {
                $this->lastStore = $store;
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $address
     * @param bool $throwException
     * @throws RuntimeException
     * @return Store
     */
    protected function getStore($address, $throwException = true)
    {
        if (!isset($this->stores[$address])) {
            $store = $this->storeRepository->findOneByAddress($address);
            if (!$store) {
                if ($throwException) {
                    throw new RuntimeException(sprintf("Store with address '%s' not found", $address));
                } else {
                    return false;
                }
            }
            $storeReference = $this->storeRepository->getReference($store->id);
            $this->stores[$address] = $storeReference;
        }

        return $this->stores[$address];
    }

    /**
     * @param array $row
     * @param Invoice $invoice
     * @return InvoiceProduct|null
     */
    protected function createInvoiceProduct(array $row, Invoice $invoice)
    {
        $invoiceProduct = null;
        if (isset($row[0], $row[1], $row[4])
            && '' != $row[0]
            && '' != $row[1]
            && '' != $row[4]
        ) {
            $sku = $this->getProductSku($row[0]);
            $productVersion = $this->getProductVersion($sku);
            $quantity = $this->normalizeQuantity($row[1]);
            $price = $this->normalizeQuantity($row[4]);

            $invoiceProduct = new InvoiceProduct();
            $invoiceProduct->invoice = $invoice;
            $invoiceProduct->product = $productVersion;
            $invoiceProduct->quantity = $this->numericFactory->createQuantity($quantity);
            $invoiceProduct->priceEntered = $this->numericFactory->createMoney($price);
        }
        return $invoiceProduct;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function normalizeQuantity($value)
    {
        return strtr(trim($value), array(',' => ''));
    }

    /**
     * @param string $value
     * @return string|null
     */
    protected function getProductSku($value)
    {
        $sku = null;
        $matches = null;
        if (preg_match('/,\s+(\S+)$/u', $value, $matches)) {
            $sku = $matches[1];
        }
        return $sku;
    }

    /**
     * @param string $sku
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     * @return ProductVersion|null
     */
    protected function getProductVersion($sku)
    {
        $product = $this->productRepository->findOneBySku($sku);
        if ($product) {
            return $this->versionFactory->createDocumentVersion($product);
        } else {
            throw new RuntimeException(sprintf("Product with sku '%s' not found", $sku));
        }
    }
}

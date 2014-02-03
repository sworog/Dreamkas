<?php

namespace Lighthouse\CoreBundle\Integration\OneC\Import\Invoices;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\Invoice\InvoiceRepository;
use Lighthouse\CoreBundle\Document\Invoice\Product\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use JMS\DiExtraBundle\Annotation as DI;
use SplFileObject;
use DateTime;

/**
 * @DI\Service("lighthouse.core.integration.onec.import.invoices.importer")
 */
class InvoicesImporter
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

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
     * @DI\InjectParams({
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "invoiceRepository" = @DI\Inject("lighthouse.core.document.repository.invoice"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "versionFactory" = @DI\Inject("lighthouse.core.versionable.factory"),
     *      "validator" = @DI\Inject("lighthouse.core.validator"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param StoreRepository $storeRepository
     * @param InvoiceRepository $invoiceRepository
     * @param ProductRepository $productRepository
     * @param VersionFactory $versionFactory
     * @param ExceptionalValidator $validator
     * @param NumericFactory $numericFactory
     */
    public function __construct(
        StoreRepository $storeRepository,
        InvoiceRepository $invoiceRepository,
        ProductRepository $productRepository,
        VersionFactory $versionFactory,
        ExceptionalValidator $validator,
        NumericFactory $numericFactory
    ) {
        $this->storeRepository = $storeRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->productRepository = $productRepository;
        $this->versionFactory = $versionFactory;
        $this->validator = $validator;
        $this->numericFactory = $numericFactory;
        $this->documentManager = $invoiceRepository->getDocumentManager();
    }

    /**
     * @param string $fileName
     * @param int $batchSize
     * @param OutputInterface $output
     */
    public function import($fileName, $batchSize = 100, OutputInterface $output = null)
    {
        $output = ($output) ?: new NullOutput();
        $dotHelper = new DotHelper($output);
        $file = new SplFileObject($fileName);
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl(',');

        $i = 0;
        $currentInvoice = null;
        foreach ($file as $row) {
            $invoice = $this->createInvoice($row);
            if ($invoice) {
                $dotHelper->writeQuestion('I');
                if ($currentInvoice) {
                    $this->documentManager->persist($currentInvoice);
                }
                $currentInvoice = $invoice;
                $this->validator->validate($currentInvoice);
                if (++$i % $batchSize) {
                    $this->documentManager->flush();
                    $dotHelper->writeInfo('F');
                }
            }
            if ($currentInvoice) {
                $invoiceProduct = $this->createInvoiceProduct($row, $currentInvoice);
                if ($invoiceProduct) {
                    $this->validator->validate($invoiceProduct);
                    $currentInvoice->products->add($invoiceProduct);
                    $dotHelper->write();
                }
            }
        }
        if ($currentInvoice) {
            $this->documentManager->persist($currentInvoice);
        }
        $this->documentManager->flush();
        $dotHelper->writeInfo('F');
    }

    /**
     * @param array $row
     * @return Invoice|null
     */
    protected function createInvoice(array $row)
    {
        $invoice = null;
        $matches = null;
        if (preg_match('/^Поступление товаров ЦБ\d+/u', $row[0], $matches)) {
            $sku = $row[7];
            $date = DateTime::createFromFormat('d.m.Y H:i:s', trim($row[6]));
            $storeName = trim($row[5]);
            $supplier = trim($row[8]);

            $invoice = new Invoice();
            $invoice->acceptanceDate = $date;
            $invoice->sku = $sku;
            $invoice->store = $this->getStore($storeName);
            $invoice->supplier = $supplier;
            $invoice->accepter = 'Импорт накладных';
            $invoice->legalEntity = 'AMN';
        }
        return $invoice;
    }

    /**
     * @param string $address
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     * @return Store
     */
    protected function getStore($address)
    {
        $store = $this->storeRepository->findOneByAddress($address);
        if (!$store) {
            throw new RuntimeException(sprintf('Store with address %s not found', $address));
        }
        return $store;
    }

    /**
     * @param array $row
     * @param Invoice $invoice
     * @return InvoiceProduct|null
     */
    protected function createInvoiceProduct(array $row, Invoice $invoice)
    {
        $invoiceProduct = null;
        if (isset($row[0], $row[2], $row[3])
            && is_numeric($row[2])
            && is_numeric($row[3])
        ) {
            $sku = $this->getProductSku($row[0]);
            $productVersion = $this->getProductVersion($sku);
            $quantity = trim($row[2]);
            $price = trim($row[3]);

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
     * @return string|null
     */
    protected function getProductSku($value)
    {
        $sku = null;
        $matches = null;
        if (preg_match('/\s+((ЦБ)?\d+)$/u', $value, $matches)) {
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
            throw new RuntimeException(sprintf('Product with sku %s not found', $sku));
        }
    }
}

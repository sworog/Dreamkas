<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoicesFilter;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class StockMovementRepository extends DocumentRepository
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }


    /**
     * @return Invoice
     */
    public function createNewInvoice()
    {
        $invoice = new Invoice();
        $invoice->sumTotal = $this->numericFactory->createMoney();
        $invoice->totalAmountVAT = $this->numericFactory->createMoney();
        $invoice->sumTotalWithoutVAT = $this->numericFactory->createMoney();

        return $invoice;
    }

    /**
     * @param string $storeId
     * @param InvoicesFilter $filter
     * @return Cursor|Invoice[]
     */
    public function findInvoicesByStore($storeId, InvoicesFilter $filter = null)
    {
        $criteria = array(
            'store' => new \MongoId($storeId),
            'type' => Invoice::TYPE
        );
        $sort = array('acceptanceDate' => self::SORT_DESC);

        if (null !== $filter && $filter->hasNumberOrSupplierInvoiceNumber()) {
            $criteria['$or'] = array(
                array('number' => $filter->getNumberOrSupplierInvoiceNumber()),
                array('supplierInvoiceNumber' => $filter->getNumberOrSupplierInvoiceNumber()),
            );
        }

        return $this->findBy($criteria, $sort);
    }

    /**
     * @param string $type
     * @return Cursor
     */
    public function findByType($type)
    {
        return $this->findBy(array('type' => $type));
    }

    /**
     * @param string $type
     * @return Cursor
     */
    public function findOneByType($type)
    {
        return $this->findOneBy(array('type' => $type));
    }
}

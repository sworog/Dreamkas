<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\ReportsBundle\Reports\GrossMarginSales\Receipt\ReceiptGrossMarginSalesManager;

/**
 * @method FirstStart find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method FirstStart findOneBy(array $criteria, array $sort = array(), array $hints = array())
 */
class FirstStartRepository extends DocumentRepository
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var ReceiptRepository
     */
    protected $receiptRepository;

    /**
     * @var ReceiptGrossMarginSalesManager
     */
    protected $receiptReportManager;

    /**
     * @param StoreRepository $storeRepository
     */
    public function setStoreRepository(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @param ReceiptRepository $receiptRepository
     */
    public function setReceiptRepository(ReceiptRepository $receiptRepository)
    {
        $this->receiptRepository = $receiptRepository;
    }

    /**
     * @param ReceiptGrossMarginSalesManager $receiptReportManager
     */
    public function setReceiptReportManager(ReceiptGrossMarginSalesManager $receiptReportManager)
    {
        $this->receiptReportManager = $receiptReportManager;
    }

    /**
     * @return FirstStart
     */
    public function findOrCreate()
    {
        $firstStart = $this->findOneBy(array());
        if (null === $firstStart) {
            $firstStart = $this->createNew();
            $this->save($firstStart);
        }
        $this->populateFirstStart($firstStart);
        return $firstStart;
    }

    /**
     * @param FirstStart $firstStart
     */
    public function populateFirstStart(FirstStart $firstStart)
    {
        if ($firstStart->complete) {
            return;
        }

        $stores = $this->storeRepository->findAllActive();

        foreach ($stores as $store) {
            $storeFirstStart = new StoreFirstStart($store);

            $this->populateStoreFirstStartInventoryCostOfGoods($storeFirstStart);
            $this->populateStoreFirstStartSale($storeFirstStart);

            $firstStart->addStoreFirstStart($storeFirstStart);
        }
    }

    /**
     * @param StoreFirstStart $storeFirstStart
     */
    public function populateStoreFirstStartInventoryCostOfGoods(StoreFirstStart $storeFirstStart)
    {
        // TODO implement
    }

    /**
     * @param StoreFirstStart $storeFirstStart
     */
    public function populateStoreFirstStartSale(StoreFirstStart $storeFirstStart)
    {
        $lastSale = $this->receiptRepository->findLastSaleByStore($storeFirstStart->store);

        if ($lastSale) {
            $storeFirstStart->sale = $this->receiptReportManager->getReceiptReport($lastSale);
        }
    }
}

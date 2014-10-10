<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance\CostOfGoods;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\SaleProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInProduct;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementProduct;
use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturnProduct;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffProduct;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalance;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @DI\Service("lighthouse.core.document.trial_balance.calculator")
 */
class CostOfGoodsCalculator
{
    /**
     * @var TrialBalanceRepository
     */
    protected $trialBalanceRepository;

    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @var StoreProductRepository
     */
    protected $storeProductRepository;

    /**
     * @var array
     */
    protected $incrementStockTypes = array(
        InvoiceProduct::TYPE,
        StockInProduct::TYPE,
        ReturnProduct::TYPE,
    );

    /**
     * @var array
     */
    protected $decrementStockTypes = array(
        SaleProduct::TYPE,
        SupplierReturnProduct::TYPE,
        WriteOffProduct::TYPE,
    );

    /**
     * @DI\InjectParams({
     *      "trialBalanceRepository" = @DI\Inject("lighthouse.core.document.repository.trial_balance"),
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory"),
     *      "storeProductRepository" = @DI\Inject("lighthouse.core.document.repository.store_product"),
     * })
     * @param TrialBalanceRepository $trialBalanceRepository
     * @param NumericFactory $numericFactory
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        TrialBalanceRepository $trialBalanceRepository,
        NumericFactory $numericFactory,
        StoreProductRepository $storeProductRepository
    ) {
        $this->trialBalanceRepository = $trialBalanceRepository;
        $this->numericFactory = $numericFactory;
        $this->storeProductRepository = $storeProductRepository;
    }

    /**
     * @param TrialBalance $trialBalance
     * @return Money
     */
    public function calculateByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->calculateByIndexRange(
            $trialBalance->storeProduct->id,
            $trialBalance->startIndex,
            $trialBalance->endIndex
        );
    }

    /**
     * @param $storeProductId
     * @param Quantity $startIndex
     * @param Quantity $endIndex
     * @return Money
     */
    public function calculateByIndexRange($storeProductId, Quantity $startIndex, Quantity $endIndex)
    {
        $invoiceProductTrials = $this->trialBalanceRepository->findByIndexRange(
            $this->getIncrementStockTypes(),
            $storeProductId,
            $startIndex,
            $endIndex
        );
        $index = $startIndex;
        $currentEndIndex = null;
        $totalCostOfGoods = $this->numericFactory->createMoney(0);
        foreach ($invoiceProductTrials as $invoiceProductTrial) {
            if ($endIndex->toNumber() > $invoiceProductTrial->endIndex->toNumber()) {
                $currentEndIndex = $invoiceProductTrial->endIndex;
            } else {
                $currentEndIndex = $endIndex;
            }
            $indexQuantity = $currentEndIndex->sub($index);
            $costOfGoods = $invoiceProductTrial->price->mul($indexQuantity);
            $totalCostOfGoods = $totalCostOfGoods->add($costOfGoods);
            $index = $index->add($indexQuantity);
        }

        if ($index->toNumber() < $endIndex->toNumber()) {
            /** @var StoreProduct $storeProduct */
            $storeProduct = $this->storeProductRepository->find($storeProductId);
            $purchasePrice = $storeProduct->lastPurchasePrice?:$storeProduct->product->purchasePrice;
            $purchasePrice = $purchasePrice?:$this->numericFactory->createQuantity(0);
            $indexQuantity = $endIndex->sub($index);
            $costOfGoods = $purchasePrice->mul($indexQuantity);
            $totalCostOfGoods = $totalCostOfGoods->add($costOfGoods);
        }

        return $totalCostOfGoods;
    }

    /**
     * @param OutputInterface|null $output
     * @return void
     */
    public function calculateUnprocessed(OutputInterface $output = null)
    {
        if (null == $output) {
            $output = new NullOutput();
        }
        $dotHelper = new DotHelper($output);

        $results = $this->trialBalanceRepository->getUnprocessedTrialBalanceGroupStoreProduct(
            $this->getSupportRangeIndex()
        );

        $dotHelper->setTotalPositions(count($results));

        foreach ($results as $result) {
            $this->calculateByStoreProductId($result['_id']['storeProduct']);

            $dotHelper->write();
        }

        $dotHelper->end();
    }

    /**
     * @param string $storeProductId
     */
    public function calculateByStoreProductId($storeProductId)
    {
        $this->calculateByStoreProductReasonTypes($storeProductId, $this->incrementStockTypes);
        $this->calculateByStoreProductReasonTypes($storeProductId, $this->decrementStockTypes);
    }

    /**
     *
     * @param string $storeProductId
     * @param array $reasonTypes
     */
    public function calculateByStoreProductReasonTypes($storeProductId, array $reasonTypes)
    {
        $trialBalance = $this->trialBalanceRepository->findOneFirstUnprocessedByStoreProductIdReasonType(
            $storeProductId,
            $reasonTypes
        );

        if (null != $trialBalance) {
            $this->calculateAndFixRangeIndexesByTrialBalance($trialBalance, $reasonTypes);
        }
    }

    /**
     * @return array
     */
    public function getIncrementStockTypes()
    {
        return $this->incrementStockTypes;
    }

    /**
     * @return array
     */
    public function getDecrementStockTypes()
    {
        return $this->decrementStockTypes;
    }

    /**
     * @return array
     */
    public function getSupportRangeIndex()
    {
        return array_merge($this->getIncrementStockTypes(), $this->getDecrementStockTypes());
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    public function isIncrementStockTypeByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->isIncrementStockType($trialBalance->reason);
    }

    /**
     * @param StockMovementProduct $stockMovementProduct
     * @return bool
     */
    public function isIncrementStockType(StockMovementProduct $stockMovementProduct)
    {
        return in_array(
            $stockMovementProduct->getType(),
            $this->getIncrementStockTypes()
        );
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    public function isDecrementStockTypeByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->isDecrementStockType($trialBalance->reason);
    }

    /**
     * @param StockMovementProduct $stockMovementProduct
     * @return bool
     */
    public function isDecrementStockType(StockMovementProduct $stockMovementProduct)
    {
        return in_array(
            $stockMovementProduct->getType(),
            $this->getDecrementStockTypes()
        );
    }

    /**
     * @param TrialBalance $trialBalance
     * @return bool
     */
    public function supportsRangeIndexByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->supportsRangeIndex($trialBalance->reason);
    }

    /**
     * @param StockMovementProduct $stockMovementProduct
     * @return bool
     */
    public function supportsRangeIndex(StockMovementProduct $stockMovementProduct)
    {
        return in_array(
            $stockMovementProduct->getType(),
            $this->getSupportRangeIndex()
        );
    }

    /**
     * @param TrialBalance $trialBalance
     * @return array
     */
    public function getStockMovementTypesByTrialBalance(TrialBalance $trialBalance)
    {
        return $this->getStockMovementTypes($trialBalance->reason);
    }

    /**
     * @param StockMovementProduct $stockMovementProduct
     * @return array
     */
    public function getStockMovementTypes(StockMovementProduct $stockMovementProduct)
    {
        if ($this->isDecrementStockType($stockMovementProduct)) {
            return $this->getDecrementStockTypes();
        } else {
            return $this->getIncrementStockTypes();
        }
    }

    /**
     * @param TrialBalance $trialBalance
     * @param array $reasonTypes
     * @param int $batch
     */
    protected function calculateAndFixRangeIndexesByTrialBalance(
        TrialBalance $trialBalance,
        array $reasonTypes,
        $batch = 1000
    ) {
        if ($this->supportsRangeIndexByTrialBalance($trialBalance)) {
            $allNeedRecalculateTrialBalance = $this
                ->trialBalanceRepository
                ->findByStartTrialBalanceDateStoreProductReasonTypes($trialBalance, $reasonTypes);
            $count = 0;
            $dm = $this->trialBalanceRepository->getDocumentManager();

            $previousTrialBalance = $this->trialBalanceRepository->findOnePreviousDateByReasonsTypes(
                $trialBalance,
                $reasonTypes
            );
            if (null != $previousTrialBalance) {
                $previousQuantity = $previousTrialBalance->endIndex;
            } else {
                $previousQuantity = $this->numericFactory->createQuantity(0);
            }

            if ($trialBalance->reason->increaseAmount()) {
                $referenceTrialBalance = $this->trialBalanceRepository->findOneByPreviousEndIndex(
                    $this->getDecrementStockTypes(),
                    $trialBalance->storeProduct->id,
                    $previousQuantity
                );
                if (null != $referenceTrialBalance) {
                    $referenceTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_UNPROCESSED;
                    $dm->persist($referenceTrialBalance);
                }
            }

            foreach ($allNeedRecalculateTrialBalance as $nextTrialBalance) {
                /** @var TrialBalance $nextTrialBalance */
                $nextTrialBalance->startIndex = $previousQuantity;
                $nextTrialBalance->endIndex = $nextTrialBalance->startIndex->add($nextTrialBalance->quantity);

                $this->calculateCostOfGoodsIfNeeded($nextTrialBalance);

                $nextTrialBalance->processingStatus = TrialBalance::PROCESSING_STATUS_OK;
                $previousQuantity = $nextTrialBalance->endIndex;
                $dm->persist($nextTrialBalance);
                $count++;
                if (1 == $batch / $count) {
                    $count = 0;
                    $dm->flush();
                    $dm->clear();
                }
            }

            $dm->flush();
            $dm->clear();
        }
    }

    /**
     * @param TrialBalance $trialBalance
     */
    protected function calculateCostOfGoodsIfNeeded(TrialBalance $trialBalance)
    {
        if ($this->isDecrementStockTypeByTrialBalance($trialBalance)) {
            $trialBalance->costOfGoods = $this->calculateByTrialBalance($trialBalance);
        }
    }
}

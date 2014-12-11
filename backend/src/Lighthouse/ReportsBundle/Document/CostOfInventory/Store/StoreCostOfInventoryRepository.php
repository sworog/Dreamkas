<?php

namespace Lighthouse\ReportsBundle\Document\CostOfInventory\Store;

use Doctrine\MongoDB\ArrayIterator;
use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceProduct;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\TrialBalance\TrialBalanceRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method StoreCostOfInventory find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 */
class StoreCostOfInventoryRepository extends DocumentRepository
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
     * @param TrialBalanceRepository $trialBalanceRepository
     */
    public function setTrialBalanceRepository(TrialBalanceRepository $trialBalanceRepository)
    {
        $this->trialBalanceRepository = $trialBalanceRepository;
    }

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @return ArrayIterator
     */
    protected function aggregateByStores()
    {
        $multiplier = $this->numericFactory->createQuantityFromCount(1)->toNumber();
        $ops = array(
            array(
                '$match' => array(
                    'reason.$ref' => InvoiceProduct::TYPE,
                ),
            ),
            array(
                '$sort' => array(
                    'createdDate.date' => self::SORT_ASC,
                )
            ),
            array(
                '$project' => array(
                    'price' => true,
                    'store' => true,
                    'inventoryCount' => '$inventory.count'
                ),
            ),
            array(
                '$group' => array(
                    '_id' => array(
                        'store' => '$store',
                    ),
                    'costOfInventory' => array(
                        '$sum' => array(
                            '$multiply' => array('$price', '$inventoryCount', $multiplier)
                        ),
                    )
                ),
            ),
        );

        return $this->trialBalanceRepository->aggregate($ops);
    }

    /**
     * @param array $result
     * @return StoreCostOfInventory
     */
    public function createByAggregateResult(array $result)
    {
        $document = new StoreCostOfInventory;
        $document->id = (string) $result['_id']['store'];
        $document->store = $this->dm->getReference(Store::getClassName(), $result['_id']['store']);
        $document->costOfInventory = $this->numericFactory->createMoneyFromCount($result['costOfInventory']);
        return $document;
    }

    /**
     * @param OutputInterface $output
     * @param int $batch
     * @return int
     */
    public function recalculate(OutputInterface $output = null, $batch = 1000)
    {
        $this->dm->clear();

        $output = $output ?: new NullOutput();
        $dotHelper = new DotHelper($output);

        $results = $this->aggregateByStores();
        $i = 0;

        $dotHelper->setTotalPositions(count($results));

        foreach ($results as $result) {
            $report = $this->createByAggregateResult($result);

            $this->dm->persist($report);

            if (++$i % $batch == 0) {
                $dotHelper->writeQuestion();
                $this->dm->flush();
                $this->dm->clear();
            } else {
                $dotHelper->write();
            }
        }

        $this->dm->flush();
        $this->dm->clear();

        $dotHelper->end();

        return $i;
    }
}

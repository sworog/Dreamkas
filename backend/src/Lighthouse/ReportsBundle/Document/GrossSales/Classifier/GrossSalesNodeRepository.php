<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Classifier;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\ReportsBundle\Reports\GrossSales\GrossSalesCalculatable;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use MongoId;

abstract class GrossSalesNodeRepository extends DocumentRepository implements GrossSalesCalculatable
{
    /**
     * @param AbstractNode $node
     * @param Store $store
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByNodeAndDayHour(AbstractNode $node, Store $store, DateTime $dayHour)
    {
        $nodeId = $this->getDocumentIdentifierValue($node);
        $storeId = $this->getDocumentIdentifierValue($store);
        return md5($nodeId . ':' . $storeId . ':' . $dayHour->getTimestamp());
    }

    /**
     * @return GrossSalesNodeReport
     */
    abstract public function createReport();

    /**
     * @param DateTime $dayHour
     * @param Group|AbstractNode $node
     * @param Store $store
     * @param Money $hourSum
     * @return GrossSalesNodeReport
     */
    public function createByDayHourAndNode(
        DateTime $dayHour,
        AbstractNode $node,
        Store $store,
        Money $hourSum = null
    ) {
        $report = $this->createReport();
        $report->id = $this->getIdByNodeAndDayHour($node, $store, $dayHour);
        $report->dayHour = $dayHour;
        $report->store = $store;
        $report->hourSum = $hourSum ?: new Money(0);
        $report->setNode($node);

        return $report;
    }

    /**
     * @param DateTime $dayHour
     * @param string $nodeId
     * @param string $storeId
     * @param Money $hourSum
     * @return GrossSalesNodeReport
     */
    public function createByDayHourAndNodeId(
        DateTime $dayHour,
        $nodeId,
        $storeId,
        Money $hourSum = null
    ) {
        $node = $this->dm->getReference($this->getNodeClass(), $nodeId);
        $store = $this->dm->getReference(Store::getClassName(), $storeId);
        return $this->createByDayHourAndNode($dayHour, $node, $store, $hourSum);
    }

    /**
     * @return string
     */
    abstract protected function getNodeClass();

    /**
     * @return string
     */
    abstract protected function getNodeField();

    /**
     * @param array $dates
     * @param array $nodeIds
     * @param string $storeId
     * @return GrossSalesNodeReport[]
     */
    public function findByDayHoursAndNodeIds(array $dates, array $nodeIds, $storeId)
    {
        $nodeIds = $this->convertToMongoIds($nodeIds);
        return $this->findBy(
            array(
                'dayHour' => array('$in' => $dates),
                $this->getNodeField() => array('$in' => $nodeIds),
                'store' => $storeId,
            )
        );
    }

    /**
     * @param array $ids
     * @param string|MongoId $storeId
     * @return array
     */
    public function calculateGrossSalesByIds(array $ids, $storeId)
    {
        $ops = array(
            array(
                '$match' => array(
                    $this->getNodeField() => array(
                        '$in' => $ids
                    ),
                    'store' => new MongoId((string) $storeId)
                ),
            ),
            array(
                '$sort' => array(
                    'dayHour' => 1,
                )
            ),
            array(
                '$project' => array(
                    '_id' => 0,
                    'dayHour' => 1,
                    'hourSum' => 1,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$dayHour',
                    'hourSum' => array('$sum' => '$hourSum'),
                ),
            ),
        );
        return $this->aggregate($ops);
    }
}

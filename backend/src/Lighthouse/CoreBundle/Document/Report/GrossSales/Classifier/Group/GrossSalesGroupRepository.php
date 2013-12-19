<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\Classifier\Group;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Report\GrossSales\GrossSalesClassifierNodeReport;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;

class GrossSalesGroupRepository extends DocumentRepository
{
    /**
     * @param AbstractNode $node
     * @param DateTime $dayHour
     * @return string
     */
    public function getIdByNodeAndDayHour(AbstractNode $node, DateTime $dayHour)
    {
        $nodeId = $this->getClassMetadata()->getIdentifierValue($node);
        return md5($nodeId . ":" . $dayHour->getTimestamp());
    }

    /**
     * @return GrossSalesClassifierNodeReport
     */
    public function createReport()
    {
        return new GrossSalesGroupReport();
    }
    /**
     * @param DateTime $dayHour
     * @param Group|AbstractNode $node
     * @param Store $store
     * @param Money $hourSum
     * @return GrossSalesGroupReport
     */
    public function createByDayHourAndNode(
        DateTime $dayHour,
        AbstractNode $node,
        Store $store,
        Money $hourSum = null
    ) {
        $report = $this->createReport();
        $report->id = $this->getIdByNodeAndDayHour($node, $dayHour);
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
     * @return GrossSalesGroupReport
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
    protected function getNodeClass()
    {
        return Group::getClassName();
    }

    protected function getNodeField()
    {
        return 'group';
    }

    /**
     * @param array $dates
     * @param array $nodeIds
     * @param string $storeId
     * @return Cursor|GrossSalesClassifierNodeReport[]
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
}

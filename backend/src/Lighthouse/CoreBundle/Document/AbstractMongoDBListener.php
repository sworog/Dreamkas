<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Types\Money;

abstract class AbstractMongoDBListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     * @param string $propertyName
     * @return int
     */
    protected function getChangeSetIntPropertyDiff(LifecycleEventArgs $eventArgs, $propertyName)
    {
        $document = $eventArgs->getDocument();
        $uow = $eventArgs->getDocumentManager()->getUnitOfWork();
        $changeSet = $uow->getDocumentChangeSet($document);
        return $this->computeDiff($changeSet, $propertyName);
    }

    /**
     * @param array $change
     * @param string $propertyName
     * @return int
     */
    protected function computeDiff(array $change, $propertyName)
    {
        if (isset($change[$propertyName])) {
            return $this->propertyToInt($change[$propertyName][1]) - $this->propertyToInt($change[$propertyName][0]);
        } else {
            return 0;
        }
    }

    /**
     * @param Money|integer $value
     * @return int
     */
    protected function propertyToInt($value)
    {
        if ($value instanceof Money) {
            return (int) $value->getCount();
        } else {
            return (int) $value;
        }
    }

    /**
     * Helper method for computing changes set
     *
     * @param DocumentManager $dm
     * @param $document
     */
    protected function computeChangeSet(DocumentManager $dm, $document, $recompute = false)
    {
        $uow = $dm->getUnitOfWork();
        $class = $dm->getClassMetadata(get_class($document));
        if ($recompute) {
            $uow->recomputeSingleDocumentChangeSet($class, $document);
        } else {
            $uow->computeChangeSet($class, $document);
        }
    }
}

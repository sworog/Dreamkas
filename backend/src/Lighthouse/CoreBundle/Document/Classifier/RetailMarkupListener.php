<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractMongoDBListener;

/**
 * @DI\DoctrineMongoDBListener(events={"onFlush"})
 */
class RetailMarkupListener extends AbstractMongoDBListener
{
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        foreach ($uow->getScheduledDocumentUpdates() as $document) {
            if ($document instanceof AbstractNode) {
                $this->processNodeChildren($dm, $document);
            }
        }
    }

    protected function processNodeChildren(DocumentManager $dm, AbstractNode $node)
    {
        $changeSet = $dm->getUnitOfWork()->getDocumentChangeSet($node);
        if ($this->hasChanged($changeSet, 'retailMarkupMin') || $this->hasChanged($changeSet, 'retailMarkupMax')) {
            $this->updateDocumentChildren($dm, $node);
        }
    }

    /**
     * @param array $changeSet
     * @param string $field
     * @return bool
     */
    protected function hasChanged(array $changeSet, $field)
    {
        if (isset($changeSet[$field]) && $changeSet[$field][0] !== $changeSet[$field][1]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param AbstractNode $document
     * @param DocumentManager $dm
     */
    protected function updateDocumentChildren(DocumentManager $dm, AbstractNode $document)
    {
        foreach ($document->getChildren() as $node) {
            if ($node->retailMarkupInherited) {
                $node->retailMarkupMin = null;
                $node->retailMarkupMax = null;

                $dm->persist($node);
                $this->computeChangeSet($dm, $node);

                $this->updateDocumentChildren($dm, $node);
            }
        }
    }
}

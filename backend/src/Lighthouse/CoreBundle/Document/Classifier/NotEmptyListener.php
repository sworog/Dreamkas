<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Exception\NotEmptyException;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class NotEmptyListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof AbstractNode) {
            $this->checkNodeHasChildrenIsEmpty($document, $eventArgs->getDocumentManager());
        }
    }

    /**
     * @param AbstractNode $node
     * @param DocumentManager $documentManager
     */
    protected function checkNodeHasChildrenIsEmpty(AbstractNode $node, DocumentManager $documentManager)
    {
        /* @var ParentableRepository $repository */
        $repository = $documentManager->getRepository($node->getChildClass());
        $shortName = $documentManager->getClassMetadata(get_class($node))->getReflectionClass()->getShortName();
        if ($repository->countByParent($node->id) > 0) {
            throw new NotEmptyException(sprintf('%s is not empty', $shortName));
        }
    }
}

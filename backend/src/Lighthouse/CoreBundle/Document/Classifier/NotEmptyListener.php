<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\CountableByParent;
use Lighthouse\CoreBundle\Exception\NotEmptyException;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class NotEmptyListener
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @DI\InjectParams({
     *      "documentManager" = @DI\Inject("doctrine.odm.mongodb.document_manager")
     * })
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof AbstractNode) {
            $this->checkNodeHasChildrenIsEmpty($document);
        }
    }

    /**
     * @param AbstractNode $node
     * @throws \Lighthouse\CoreBundle\Exception\NotEmptyException
     */
    protected function checkNodeHasChildrenIsEmpty(AbstractNode $node)
    {
        /* @var CountableByParent $repository */
        $repository = $this->documentManager->getRepository($node->getChildClass());
        $shortName = $this->documentManager->getClassMetadata(get_class($node))->getReflectionClass()->getShortName();
        if ($repository->countByParent($node->id) > 0) {
            throw new NotEmptyException(sprintf('%s is not empty', $shortName));
        }
    }
}

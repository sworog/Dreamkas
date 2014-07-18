<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Exception\NotEmptyException;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove", "postSoftDelete"})
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
    public function __construct(DocumentManager $documentManager)
    {
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
     * @throws NotEmptyException
     */
    protected function checkNodeHasChildrenIsEmpty(AbstractNode $node)
    {
        /* @var ParentableRepository $repository */
        $repository = $this->documentManager->getRepository($node->getChildClass());
        $shortName = $this->documentManager->getClassMetadata(get_class($node))->getReflectionClass()->getShortName();
        if ($repository->countByParent($node->id) > 0) {
            throw new NotEmptyException(sprintf('%s is not empty', $shortName));
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if ($document instanceof SubCategory) {

            $oldValue = $document->name;
            $document->name .= sprintf(' (Удалено %s)', $document->deletedAt->format('Y-m-d H:i:s'));

            $uow = $eventArgs->getDocumentManager()->getUnitOfWork();
            $uow->propertyChanged($document, 'name', $oldValue, $document->name);
            $uow->scheduleExtraUpdate(
                $document,
                array(
                    'name' => array($oldValue, $document->name)
                )
            );
        }
    }
}

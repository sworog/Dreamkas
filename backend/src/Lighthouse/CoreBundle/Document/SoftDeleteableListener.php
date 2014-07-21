<?php

namespace Lighthouse\CoreBundle\Document;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\MongoDB\DocumentManager;

/**
 * @DI\DoctrineMongoDBListener(events={"postSoftDelete"})
 */
class SoftDeleteableListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        /* @var DocumentManager $db */
        $dm = $eventArgs->getDocumentManager();
        $metadata = $dm->getClassMetadata(get_class($document));

        if ($metadata->softDeleteable) {

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

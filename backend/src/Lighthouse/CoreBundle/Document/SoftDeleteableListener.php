<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\UnitOfWork;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\MongoDB\DocumentManager;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @@DI\Service("lighthouse.core.document.softdeleteable.listener")
 * @@DI\DoctrineMongoDBListener(events={"postSoftDelete"})
 */
class SoftDeleteableListener
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * @DI\InjectParams({
     *      "accessor" = @DI\Inject("property_accessor")
     * })
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postSoftDelete(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        /* @var DocumentManager $dm */
        $dm = $eventArgs->getDocumentManager();
        $uow = $dm->getUnitOfWork();

        $metadata = $dm->getClassMetadata(get_class($document));

        if ($metadata->softDeleteable && $document instanceof SoftDeleteableDocument) {

            $nameField = $document->getSoftDeleteableName();

            if (null !== $nameField) {
                $oldValue = $this->accessor->getValue($document, $nameField);
                $newValue = $oldValue . sprintf(' (Удалено %s)', $document->getDeletedAt()->format('Y-m-d H:i:s'));
                $this->accessor->setValue($document, $nameField, $newValue);

                $this->schedulePropertyChange($uow, $document, $nameField, $oldValue, $newValue);
            }
        }
    }

    /**
     * @param UnitOfWork $uow
     * @param object $object
     * @param string $field
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    protected function schedulePropertyChange(UnitOfWork $uow, $object, $field, $oldValue, $newValue)
    {
        $uow->propertyChanged($object, $field, $oldValue, $newValue);
        $uow->scheduleExtraUpdate(
            $object,
            array(
                $field => array($oldValue, $newValue)
            )
        );
    }
}

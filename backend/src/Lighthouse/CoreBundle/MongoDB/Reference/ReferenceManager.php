<?php

namespace Lighthouse\CoreBundle\MongoDB\Reference;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.mongodb.reference.manager")
 * @DI\DoctrineMongoDBListener(events={"postLoad", "prePersist", "preUpdate"})
 */
class ReferenceManager
{
    /**
     * @var ReferenceProviderInterface[]
     */
    protected $referenceProviders = array();

    /**
     * @param ReferenceProviderInterface[] $referenceProviders
     */
    public function __construct(array $referenceProviders = array())
    {
        $this->setReferenceProviders($referenceProviders);
    }

    /**
     * @param ReferenceProviderInterface $referenceProvider
     * @param string $alias
     */
    public function addReferenceProvider($alias, ReferenceProviderInterface $referenceProvider)
    {
        $this->referenceProviders[$alias] = $referenceProvider;
    }

    /**
     * @param ReferenceProviderInterface[] $referenceProviders
     */
    public function setReferenceProviders(array $referenceProviders)
    {
        $this->referenceProviders = array();
        foreach ($referenceProviders as $alias => $referenceProvider) {
            $this->addReferenceProvider($alias, $referenceProvider);
        }
    }

    /**
     * @return array|ReferenceProviderInterface[]
     */
    public function getReferenceProviders()
    {
        return $this->referenceProviders;
    }

    /**
     * @param string $alias
     * @return ReferenceProviderInterface
     */
    public function getReferenceProvider($alias)
    {
        return $this->referenceProviders[$alias];
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->loadReference($eventArgs->getDocument(), $eventArgs->getDocumentManager());
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->updateReference($eventArgs->getDocument(), $eventArgs->getDocumentManager(), false);
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->updateReference($eventArgs->getDocument(), $eventArgs->getDocumentManager(), true);
    }

    /**
     * @param $document
     * @param ObjectManager $manager
     */
    protected function loadReference($document, ObjectManager $manager)
    {
        foreach ($this->referenceProviders as $referenceProvider) {
            if ($referenceProvider->supports($document)) {

                $field = $referenceProvider->getReferenceField();
                $identifier = $referenceProvider->getIdentifier();
                $metadata = $this->getClassMetadata($document, $manager);

                $refObjectId = $metadata->getFieldValue($document, $identifier);
                $refObject = $referenceProvider->getRefObject($refObjectId);

                $property = $metadata->getReflectionClass()->getProperty($field);
                $property->setAccessible(true);
                $property->setValue($document, $refObject);
            }
        }
    }

    /**
     * @param $document
     * @param ObjectManager|DocumentManager $manager
     * @param bool $recompute
     */
    protected function updateReference($document, ObjectManager $manager, $recompute = false)
    {
        foreach ($this->referenceProviders as $referenceProvider) {
            if ($referenceProvider->supports($document)) {

                $field = $referenceProvider->getReferenceField();
                $identifier = $referenceProvider->getIdentifier();

                $metadata = $this->getClassMetadata($document, $manager);

                $property = $metadata->getReflectionClass()->getProperty($field);
                $property->setAccessible(true);

                $refObject = $property->getValue($document);

                if (null !== $refObject) {
                    $refObjectId = $referenceProvider->getRefObjectId($refObject);
                    $metadata->setFieldValue($document, $identifier, $refObjectId);
                }

                if ($recompute) {
                    $manager->getUnitOfWork()->recomputeSingleDocumentChangeSet($metadata, $document);
                }
            }
        }
    }

    /**
     * @param $document
     * @param ObjectManager $manager
     * @return ClassMetadata|\Doctrine\ODM\MongoDB\Mapping\ClassMetadata
     */
    protected function getClassMetadata($document, ObjectManager $manager)
    {
        return $manager->getClassMetadata(get_class($document));
    }
}

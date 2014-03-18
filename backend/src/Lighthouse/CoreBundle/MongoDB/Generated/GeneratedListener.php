<?php

namespace Lighthouse\CoreBundle\MongoDB\Generated;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\MongoDB\Event\CreateCollectionEventArgs;
use Doctrine\MongoDB\Event\EventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist", "preCreateCollection"})
 */
class GeneratedListener
{
    /**
     * @var IncrementGenerator
     */
    protected $generator;

    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @DI\InjectParams({
     *      "generator" = @DI\Inject("lighthouse.core.mongodb.generator.increment"),
     *      "accessor" = @DI\Inject("property_accessor"),
     *      "managerRegistry" = @DI\Inject("doctrine_mongodb")
     * })
     */
    public function __construct(
        IncrementGenerator $generator,
        PropertyAccessorInterface $accessor,
        ManagerRegistry $managerRegistry
    ) {
        $this->generator = $generator;
        $this->accessor = $accessor;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param DocumentManager $documentManager
     * @param string $collectionName
     * @return array[ClassMetadata]
     */
    protected function getGeneratedMappings(DocumentManager $documentManager, $collectionName)
    {
        $mappings = array();
        /* @var ClassMetadata[] $allMetaData */
        $allMetaData = $documentManager->getMetadataFactory()->getAllMetadata();
        foreach ($allMetaData as $metaData) {
            if ($metaData->getCollection() === $collectionName) {
                foreach ($metaData->fieldMappings as $fieldMapping) {
                    if (isset($fieldMapping['generated']) && true == $fieldMapping['generated']) {
                        $mappings[] = array($metaData, $fieldMapping);
                    }
                }
            }
        }
        return $mappings;
    }

    public function preCreateCollection(CreateCollectionEventArgs $eventArgs)
    {
        /* @var DocumentManager $documentManager */
        $documentManager = $this->managerRegistry->getManager();

        foreach ($this->getGeneratedMappings($documentManager, $eventArgs->getName()) as $mapping) {
            /* @var ClassMetadata $metadata*/
            list($metadata, $fieldMapping) = $mapping;
            $this->generator->setStartValue($documentManager, $metadata->getName(), $fieldMapping['startValue']);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        $dm = $eventArgs->getDocumentManager();
        $metaData = $dm->getClassMetadata(get_class($document));
        foreach ($metaData->fieldMappings as $mapping) {
            if (isset($mapping['generated']) && true == $mapping['generated']) {
                $this->generate($document, $mapping['name'], $dm);
            }
        }
    }

    /**
     * @param $document
     * @param string $propertyPath
     * @param DocumentManager $dm
     */
    protected function generate($document, $propertyPath, DocumentManager $dm)
    {
        $value = $this->generator->generate($dm, $document);
        $this->accessor->setValue($document, $propertyPath, $value);
    }
}

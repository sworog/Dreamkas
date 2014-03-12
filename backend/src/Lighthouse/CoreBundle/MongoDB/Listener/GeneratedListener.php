<?php

namespace Lighthouse\CoreBundle\MongoDB\Listener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Id\IncrementGenerator;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @DI\DoctrineMongoDBListener(events={"prePersist"})
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
     * @DI\InjectParams({
     *      "generator" = @DI\Inject("doctrine_mongodb.odm.generator.increment"),
     *      "accessor" = @DI\Inject("property_accessor")
     * })
     */
    public function __construct(IncrementGenerator $generator, PropertyAccessorInterface $accessor)
    {
        $this->generator = $generator;
        $this->accessor = $accessor;
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

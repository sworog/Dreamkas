<?php

namespace Lighthouse\CoreBundle\Serializer\Metadata\Driver;

use FOS\RestBundle\Util\Pluralization;
use Metadata\Driver\DriverInterface;
use Metadata\ClassMetadata;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.serializer.metadata.driver.document")
 */
class DocumentDriver implements DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $delegate;

    /**
     * @DI\InjectParams({
     *      "delegate" = @DI\Inject("jms_serializer.metadata.doctrine_type_driver")
     * })
     * @param DriverInterface $delegate
     */
    public function __construct(DriverInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param ReflectionClass $class
     * @return \Metadata\ClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = $this->delegate->loadMetadataForClass($class);
        if ($class->isSubclassOf('\\Lighthouse\\CoreBundle\\Document\\AbstractDocument')) {
            $this->processDocumentMetadata($metadata);
        } elseif ($class->isSubclassOf('\\Lighthouse\\CoreBundle\\Document\\AbstractCollection')) {
            $this->processCollectionMetadata($metadata);
        }
        return $metadata;
    }

    /**
     * @param ClassMetadata $metadata
     */
    protected function processCollectionMetadata(ClassMetadata $metadata)
    {
        if (null === $metadata->xmlRootName) {
            $className = $metadata->reflection->getShortName();
            $metadata->xmlRootName = $this->getCollectionTagName($className);
        }
    }

    /**
     * @param ClassMetadata $metadata
     */
    protected function processDocumentMetadata(ClassMetadata $metadata)
    {
        if (null === $metadata->xmlRootName) {
            $className = $metadata->reflection->getShortName();
            $metadata->xmlRootName = $this->getItemTagName($className);
        }
    }

    /**
     * @param string $className
     * @return string
     */
    protected function getCollectionTagName($className)
    {
        $className = str_replace('Collection', '', $className);
        $className = lcfirst($className);
        return Pluralization::pluralize($className);
    }

    /**
     * @param $className
     * @return string
     */
    protected function getItemTagName($className)
    {
        $className = lcfirst($className);
        return $className;
    }
}

<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use FOS\RestBundle\Util\Pluralization;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\VisitorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\XmlSerializationVisitor;
use Lighthouse\CoreBundle\Document\AbstractCollection;
use Metadata\MetadataFactoryInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.collection")
 * @DI\Tag("jms_serializer.subscribing_handler")
 */
class CollectionHandler implements SubscribingHandlerInterface
{
    /**
     * @DI\Inject("jms_serializer.metadata_factory")
     * @var MetadataFactoryInterface
     */
    public $metadataFactory;

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('xml' => 'Xml');
        foreach ($formats as $format => $methodSuffix) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => $format,
                'type' => 'Collection',
                'method' => 'serializeCollectionTo' . $methodSuffix,
            );
        }
        return $methods;
    }

    /**
     * @param \JMS\Serializer\VisitorInterface $visitor
     * @param AbstractCollection $value
     * @param array $type
     * @return string
     */
    public function serializeCollectionToXml(XmlSerializationVisitor $visitor, AbstractCollection $collection, array $type)
    {
        /* @var ClassMetadata $collectionMetadata  */
        $collectionMetadata = $this->metadataFactory->getMetadataForClass(get_class($collection));

        if (null === $collectionMetadata->xmlRootName) {
            $className = $collectionMetadata->reflection->getShortName();
            $collectionMetadata->xmlRootName = $this->getCollectionTagName($className);
        }

        $visitor->startVisitingObject($collectionMetadata, $collection, $type);

        foreach ($collection->toArray() as $item) {
            $itemMetadata = $this->metadataFactory->getMetadataForClass(get_class($item));

            if (null === $itemMetadata->xmlRootName) {
                $className = $itemMetadata->reflection->getShortName();
                $itemMetadata->xmlRootName = $this->getItemTagName($className);
            }

            $itemNode = $visitor->document->createElement($itemMetadata->xmlRootName);
            $visitor->getCurrentNode()->appendChild($itemNode);
            $visitor->setCurrentNode($itemNode);

            $visitor->getNavigator()->accept($item, null, $visitor);

            $visitor->revertCurrentNode();
        }
    }

    /**
     * @param AbstractCollection $collection
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
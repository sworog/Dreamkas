<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Metadata\ClassMetadata;
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
     * @param XmlSerializationVisitor $visitor
     * @param \Lighthouse\CoreBundle\Document\AbstractCollection $collection
     * @param array $type
     */
    public function serializeCollectionToXml(
        XmlSerializationVisitor $visitor,
        AbstractCollection $collection,
        array $type
    ) {
        /* @var ClassMetadata $collectionMetadata  */
        $collectionMetadata = $this->metadataFactory->getMetadataForClass(get_class($collection));

        $visitor->startVisitingObject($collectionMetadata, $collection, $type);

        foreach ($collection->toArray() as $item) {
            $itemMetadata = $this->metadataFactory->getMetadataForClass(get_class($item));

            $itemNode = $visitor->document->createElement($itemMetadata->xmlRootName);
            $visitor->getCurrentNode()->appendChild($itemNode);
            $visitor->setCurrentNode($itemNode);

            $visitor->getNavigator()->accept($item, null, $visitor);

            $visitor->revertCurrentNode();
        }
    }
}

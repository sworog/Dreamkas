<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\XmlSerializationVisitor;
use Lighthouse\CoreBundle\Document\AbstractCollection;
use Metadata\MetadataFactoryInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.collection")
 * @DI\Tag("jms_serializer.subscribing_handler")
 * @DI\Tag("jms_serializer.event_subscriber")
 */
class CollectionHandler implements SubscribingHandlerInterface, EventSubscriberInterface
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
        $formats = array('xml' => 'Xml', 'json' => 'Json');
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
     * @param Context $context
     */
    public function serializeCollectionToXml(
        XmlSerializationVisitor $visitor,
        AbstractCollection $collection,
        array $type,
        Context $context
    ) {
        /* @var ClassMetadata $collectionMetadata  */
        $collectionMetadata = $this->metadataFactory->getMetadataForClass(get_class($collection));

        $visitor->startVisitingObject($collectionMetadata, $collection, $type, $context);

        foreach ($collection->toArray() as $item) {
            /* @var ClassMetadata $itemMetadata */
            $itemMetadata = $this->metadataFactory->getMetadataForClass(get_class($item));

            $itemNode = $visitor->document->createElement($itemMetadata->xmlRootName);
            $visitor->getCurrentNode()->appendChild($itemNode);
            $visitor->setCurrentNode($itemNode);

            $visitor->getNavigator()->accept($item, null, $context);

            $visitor->revertCurrentNode();
        }
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param AbstractCollection $collection
     * @param array $type
     * @param Context $context
     */
    public function serializeCollectionToJson(
        JsonSerializationVisitor $visitor,
        AbstractCollection $collection,
        array $type,
        Context $context
    ) {
        $visitor->visitArray($collection->toArray(), $type, $context);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => Events::PRE_SERIALIZE,
                'method' => 'onPreSerialize'
            ),
        );
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof AbstractCollection) {
            $event->setType('Collection');
        }
    }
}

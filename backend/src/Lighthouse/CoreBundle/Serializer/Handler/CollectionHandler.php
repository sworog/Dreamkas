<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\XmlSerializationVisitor;
use Lighthouse\CoreBundle\Document\AbstractCollection;
use Metadata\MetadataFactoryInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.collection")
 * @DI\Tag("jms_serializer.handler", attributes={
 *      "type": "Collection",
 *      "format": "json",
 *      "direction": "serialization"
 * })
 * @DI\Tag("jms_serializer.handler", attributes={
 *      "type": "Collection",
 *      "format": "xml",
 *      "direction": "serialization"
 * })
 * @DI\Tag("jms_serializer.event_subscriber")
 */
class CollectionHandler implements EventSubscriberInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @DI\InjectParams({
     *      "metadataFactory" = @DI\Inject("jms_serializer.metadata_factory")
     * })
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
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

            /* @var \DOMDocument $document */
            $document = $visitor->document;
            $itemNode = $document->createElement($itemMetadata->xmlRootName);
            /* @var \DOMDocument $currentNode */
            $currentNode = $visitor->getCurrentNode();
            $currentNode->appendChild($itemNode);
            $visitor->setCurrentNode($itemNode);

            /* @var GraphNavigator $navigator */
            $navigator = $visitor->getNavigator();
            $navigator->accept($item, null, $context);

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

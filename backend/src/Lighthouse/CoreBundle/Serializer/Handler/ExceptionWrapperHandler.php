<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use FOS\RestBundle\Util\ExceptionWrapper;
use JMS\Serializer\Context;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\VisitorInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.exception_wrapper")
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "ExceptionWrapper",
 *          "format": "json",
 *          "direction": "serialization"
 *      }
 * )
 * @DI\Tag(
 *      "jms_serializer.event_listener",
 *      attributes={
 *          "event": "serializer.pre_serialize"
 *      }
 * )
 */
class ExceptionWrapperHandler
{
    const TYPE = 'ExceptionWrapper';

    /**
     * @param VisitorInterface $visitor
     * @param ExceptionWrapper $value
     * @param array $type
     * @param Context $context
     * @return string
     */
    public function serializeExceptionWrapperToJson(
        VisitorInterface $visitor,
        ExceptionWrapper $value,
        array $type,
        Context $context
    ) {
        /* @var ClassMetadata $metadata */
        $metadata = $context->getMetadataFactory()->getMetadataForClass(get_class($value));

        $visitor->startVisitingObject($metadata, $value, $type, $context);

        /* @var PropertyMetadata $propertyMetadata */
        foreach ($metadata->propertyMetadata as $propertyMetadata) {
            $context->pushPropertyMetadata($propertyMetadata);
            $visitor->visitProperty($propertyMetadata, $value, $context);
            $context->popPropertyMetadata();
        }

        return $visitor->endVisitingObject($metadata, $value, $type, $context);
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onSerializerPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof ExceptionWrapper) {
            $event->setType(self::TYPE);
        }
    }
}

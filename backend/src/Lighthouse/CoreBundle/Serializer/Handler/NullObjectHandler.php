<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\VisitorInterface;
use Lighthouse\CoreBundle\Document\NullObjectInterface;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Numeric;

/**
 * @DI\Service("lighthouse.core.serializer.handler.null_object")
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "NullObject",
 *          "format": "json",
 *          "direction": "serialization"
 *      }
 * )
 * @DI\Tag("jms_serializer.event_listener", attributes={
 *      "event": "serializer.pre_serialize"
 * })
 */
class NullObjectHandler
{
    const TYPE = 'NullObject';

    /**
     * @param VisitorInterface $visitor
     * @param NullObjectInterface $value
     * @param array $type
     * @param Context $context
     * @return string
     */
    public function serializeNullObjectToJson(
        VisitorInterface $visitor,
        NullObjectInterface $value,
        array $type,
        Context $context
    ) {
        return $visitor->visitNull($value, $type, $context);
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onSerializerPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof NullObjectInterface) {
            $event->setType(self::TYPE);
        }
    }
}

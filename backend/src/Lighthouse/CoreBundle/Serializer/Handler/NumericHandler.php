<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\VisitorInterface;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\Numeric;

/**
 * @DI\Service("lighthouse.core.serializer.handler.decimal")
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "Numeric",
 *          "format": "json",
 *          "direction": "serialization"
 *      }
 * )
 * @DI\Tag("jms_serializer.event_listener", attributes={
 *      "event": "serializer.pre_serialize"
 * })
 */
class NumericHandler
{
    /**
     * @param VisitorInterface $visitor
     * @param Numeric $value
     * @param array $type
     * @param Context $context
     * @return string
     */
    public function serializeNumericToJson(VisitorInterface $visitor, Numeric $value, array $type, Context $context)
    {
        return $value->toString();
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onSerializerPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof Numeric && !$event->getObject() instanceof Money) {
            $event->setType(Numeric::TYPE);
        }
    }
}

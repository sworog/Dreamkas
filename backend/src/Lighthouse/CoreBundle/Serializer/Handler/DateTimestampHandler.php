<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Lighthouse\CoreBundle\Types\Date\DateTimestamp;

/**
 * @DI\Service("lighthouse.core.serializer.handler.date_timestamp")
 * @DI\Tag("jms_serializer.event_listener", attributes={
 *      "event": "serializer.pre_serialize"
 * })
 */
class DateTimestampHandler
{
    /**
     * @param PreSerializeEvent $event
     */
    public function onSerializerPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof DateTimestamp) {
            $event->setType("DateTime");
        }
    }
}

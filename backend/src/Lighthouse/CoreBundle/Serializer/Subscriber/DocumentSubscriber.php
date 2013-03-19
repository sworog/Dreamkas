<?php

namespace Lighthouse\CoreBundle\Serializer\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @DI\Service("lighthouse.core.serializer.subscriber.document")
 * @DI\Tag("jms_serializer.event_subscriber")
 */
class DocumentSubscriber implements EventSubscriberInterface
{
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
        if ($event->getObject() instanceof AbstractDocument) {
            $event->setType('Document');
        }
    }
}
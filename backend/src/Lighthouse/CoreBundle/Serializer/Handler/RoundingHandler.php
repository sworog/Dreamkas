<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\VisitorInterface;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.rounding")
 * @DI\Tag("jms_serializer.handler", attributes={
 *      "type": "Rounding",
 *      "format": "json",
 *      "direction": "serialization",
 * })
 * @DI\Tag("jms_serializer.event_listener", attributes={
 *      "event": "serializer.pre_serialize"
 * })
 */
class RoundingHandler
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @DI\InjectParams({
     *      "translator" = @DI\Inject("translator")
     * })
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param VisitorInterface|JsonSerializationVisitor $visitor
     * @param AbstractRounding $value
     * @return array
     */
    public function serializeRoundingToJson(VisitorInterface $visitor, AbstractRounding $value)
    {
        $title = $this->translator->trans($value->getTitle(), array(), 'rounding');

        $data = array(
            'name' => $value->getName(),
            'title' => $title,
        );

        if (null === $visitor->getRoot()) {
            $visitor->setRoot($data);
        }

        return $data;
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onSerializerPreSerialize(PreSerializeEvent $event)
    {
        if ($event->getObject() instanceof AbstractRounding) {
            $event->setType(AbstractRounding::TYPE);
        }
    }
}

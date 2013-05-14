<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyViewTransformer;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.serializer.handler.money")
 * @DI\Tag("jms_serializer.subscribing_handler")
 */
class MoneyHandler implements SubscribingHandlerInterface
{
    /**
     * @DI\Inject("lighthouse.core.data_transformer.money_model")
     * @var MoneyModelTransformer
     */
    public $modelTransformer;

    /**
     * @DI\Inject("lighthouse.core.data_transformer.float_view")
     * @var FloatViewTransformer
     */
    public $viewTransformer;

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('json', 'xml', 'yml');
        $types = array('Lighthouse\\CoreBundle\\Types\\Money', 'Money');
        foreach ($formats as $format) {
            foreach ($types as $type) {
                $methods[] = array(
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'format' => $format,
                    'type' => $type,
                    'method' => 'serializeMoney',
                );
            }
        }
        return $methods;
    }

    /**
     * @param \JMS\Serializer\VisitorInterface $visitor
     * @param Money $value
     * @param array $type
     * @param Context $context
     * @return string|null
     */
    public function serializeMoney(VisitorInterface $visitor, Money $value, array $type, Context $context)
    {
        $normData = $this->modelTransformer->transform($value);
        $viewData = $this->viewTransformer->transform($normData);
        if (null === $viewData) {
            $serialized = $visitor->visitNull($viewData, $type, $context);
        } else {
            $serialized = $visitor->visitString($viewData, $type, $context);
        }
        return $serialized;
    }
}

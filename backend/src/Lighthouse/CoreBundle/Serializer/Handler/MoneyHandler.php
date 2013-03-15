<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

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
    public $moneyTransformer;

    /**
     * @DI\Inject("lighthouse.core.data_transformer.money_view")
     * @var MoneyViewTransformer
     */
    public $viewTransformer;

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('json', 'xml', 'yml');
        foreach ($formats as $format) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => $format,
                'type' => 'Money',
                'method' => 'serializeMoney',
            );
            /*
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'type' => 'Money',
                'method' => 'deserializeMoney',
            );
            */
        }
        return $methods;
    }

    /**
     * @param \JMS\Serializer\VisitorInterface $visitor
     * @param Money $value
     * @param array $type
     * @return string
     */
    public function serializeMoney(VisitorInterface $visitor, Money $value, array $type)
    {
        $normData = $this->moneyTransformer->transform($value);
        $viewData = $this->viewTransformer->transform($normData);
        return $visitor->visitString($viewData, $type);
    }

    /**
     * Пока не нужен
     * @param \JMS\Serializer\VisitorInterface $visitor
     * @param string $value
     * @param array $type
     * @return int
     */
    /*
    public function deserializeMoney(VisitorInterface $visitor, $value, array $type)
    {
        $value = $visitor->visitString($value, $type);
        $normData = $this->viewTransformer->reverseTransform($value);
        $modelData = $this->moneyTransformer->reverseTransform($normData);
        return $modelData;
    }
    */
}

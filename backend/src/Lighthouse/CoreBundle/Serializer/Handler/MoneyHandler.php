<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\VisitorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\DataTransformer\FloatViewTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Types\Money;

/**
 * @DI\Service("lighthouse.core.serializer.handler.money")
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "Lighthouse\CoreBundle\Types\Money",
 *          "format": "json",
 *          "direction": "serialization",
 *          "method": "serializeMoney"
 *      }
 * )
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "Lighthouse\CoreBundle\Types\Money",
 *          "format": "xml",
 *          "direction": "serialization",
 *          "method": "serializeMoney"
 *      }
 * )
 */
class MoneyHandler
{
    /**
     * @var MoneyModelTransformer
     */
    protected $modelTransformer;

    /**
     * @var FloatViewTransformer
     */
    protected $viewTransformer;

    /**
     * @DI\InjectParams({
     *      "modelTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model"),
     *      "viewTransformer" = @DI\Inject("lighthouse.core.data_transformer.float_view")
     * })
     * @param MoneyModelTransformer $modelTransformer
     * @param FloatViewTransformer $viewTransformer
     */
    public function __construct(MoneyModelTransformer $modelTransformer, FloatViewTransformer $viewTransformer)
    {
        $this->modelTransformer = $modelTransformer;
        $this->viewTransformer = $viewTransformer;
    }

    /**
     * @param VisitorInterface $visitor
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

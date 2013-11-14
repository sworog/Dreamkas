<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\VisitorInterface;
use Lighthouse\CoreBundle\Types\Decimal;

/**
 * @DI\Service("lighthouse.core.serializer.handler.decimal")
 * @DI\Tag(
 *      "jms_serializer.handler",
 *      attributes={
 *          "type": "Lighthouse\CoreBundle\Types\Decimal",
 *          "format": "json",
 *          "direction": "serialization"
 *      }
 * )
 */
class DecimalHandler
{
    /**
     * @param VisitorInterface $visitor
     * @param Decimal $value
     * @param array $type
     * @param Context $context
     * @return string
     */
    public function serializeDecimalToJson(VisitorInterface $visitor, Decimal $value, array $type, Context $context)
    {
        return $value->toString();
    }
}

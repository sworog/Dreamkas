<?php

namespace Lighthouse\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.data_transformer.money")
 */
class PriceModelTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        return sprintf("%.2f", $value / 100);
    }

    /**
     * @param mixed $value
     * @return int
     */
    public function reverseTransform($value)
    {
        return floor($value * 100);
    }
}

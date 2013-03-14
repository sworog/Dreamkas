<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

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
        return new Money($value);
    }
}

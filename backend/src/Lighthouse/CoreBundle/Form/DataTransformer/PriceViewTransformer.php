<?php

namespace Lighthouse\CoreBundle\Form\DataTransformer;

use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.data_transformer.money")
 */
class PriceViewTransformer implements DataTransformerInterface
{
    /**
     * @param Money $value
     * @return mixed
     */
    public function transform($value)
    {
        if ($value instanceof Money) {
            return $value->getCount() / 100;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function reverseTransform($value)
    {
        return new Money(str_replace(',', '.', (string) $value) * 100);
    }
}

<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.data_transformer.money")
 */
class PriceViewTransformer implements DataTransformerInterface
{
    /**
     * @var int
     */
    protected $digits = 2;

    /**
     * @param Money $value
     * @return mixed
     */
    public function transform($value)
    {
        if ($value instanceof Money) {
            return $value->getCount() / pow(10, $this->digits);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return Money
     */
    public function reverseTransform($value)
    {
        $money = str_replace(',', '.', (string) $value) * pow(10, $this->digits);
        return new Money($money);
    }
}

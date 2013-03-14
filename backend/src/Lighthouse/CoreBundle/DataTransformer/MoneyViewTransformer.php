<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.data_transformer.money_view")
 */
class MoneyViewTransformer implements DataTransformerInterface
{
    /**
     * @var int
     */
    protected $digits = 2;

    /**
     * @param int $divider
     */
    public function __construct($digits = null)
    {
        if (null !== $digits) {
            $this->digits = (int) $digits;
        }
    }

    /**
     * @param int $value
     * @return string
     */
    public function transform($value)
    {
        //$value = sprintf("%01.{$this->digits}f", $value);
        return $value;
    }

    /**
     * @param float $value
     * @return int
     */
    public function reverseTransform($value)
    {
        $money = str_replace(',', '.', (string) $value);
        return $money;
    }
}

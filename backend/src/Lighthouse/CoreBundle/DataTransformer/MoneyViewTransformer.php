<?php

namespace Lighthouse\CoreBundle\DataTransformer;

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
     * @param int $digits
     */
    public function __construct($digits = null)
    {
        if (null !== $digits) {
            $this->digits = $digits;
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
        if (null !== $value) {
            $value = str_replace(',', '.', (string) $value);
        }
        return $value;
    }
}

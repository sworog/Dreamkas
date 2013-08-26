<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @DI\Service("lighthouse.core.data_transformer.money_model")
 */
class MoneyModelTransformer implements DataTransformerInterface
{
    /**
     * @var int
     */
    protected $precision = 2;

    /**
     * @DI\InjectParams({
     *      "precision"=@DI\Inject("%lighthouse.core.money.precision%")
     * })
     * @param int $precision
     */
    public function __construct($precision = null)
    {
        if (null !== $precision) {
            $this->precision = (int) $precision;
        }
    }

    /**
     * @param int $precision
     * @return number
     */
    protected function getDivider($precision)
    {
        return pow(10, $precision);
    }

    /**
     * @param int $precision
     * @return int
     */
    protected function getPrecision($precision = null)
    {
        return ($precision) ? (int) $precision : $this->precision;
    }

    /**
     * @param Money $value
     * @param int $precision
     * @return string
     * @throws TransformationFailedException
     */
    public function transform($value, $precision = null)
    {
        if (null === $value) {
            $value = null;
        } elseif ($value instanceof Money) {
            if ($value->isNull()) {
                return null;
            } else {
                $precision = $this->getPrecision($precision);
                $divider = $this->getDivider($precision);
                $value = $value->getCount() / $divider;
                $value = sprintf("%.{$precision}f", $value);
            }
        } else {
            throw new TransformationFailedException(
                'Value should be Money type object or null. ' . gettype($value) . ' given'
            );
        }
        return $value;
    }

    /**
     * @param mixed $value
     * @param int $precision
     * @return Money|mixed
     */
    public function reverseTransform($value, $precision = null)
    {
        if (null !== $value && '' !== $value) {
            $precision = $this->getPrecision($precision);
            $divider = $this->getDivider($precision);
            $value = $value * $divider;
            $value = (float) (string) $value;
        }
        return new Money($value);
    }
}

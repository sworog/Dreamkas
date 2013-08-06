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
    protected $digits = 2;

    /**
     * @DI\InjectParams({
     *      "digits"=@DI\Inject("%money.digits%")
     * })
     * @param int $digits
     */
    public function __construct($digits = null)
    {
        if (null !== $digits) {
            $this->digits = (int) $digits;
        }
    }

    /**
     * @param int $digits
     * @return number
     */
    protected function getDivider($digits)
    {
        return pow(10, $digits);
    }

    /**
     * @param int $digits
     * @return int
     */
    protected function getDigits($digits = null)
    {
        return ($digits) ? (int) $digits : $this->digits;
    }

    /**
     * @param Money $value
     * @param int $digits
     * @return int
     * @throws TransformationFailedException
     */
    public function transform($value, $digits = null)
    {
        if (null === $value) {
            $value = null;
        } elseif ($value instanceof Money) {
            if ($value->isNull()) {
                return null;
            } else {
                $digits = $this->getDigits($digits);
                $divider = $this->getDivider($digits);
                $value = $value->getCount() / $divider;
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
     * @param int $digits
     * @return Money|mixed
     */
    public function reverseTransform($value, $digits = null)
    {
        if (null !== $value && '' !== $value) {
            $digits = $this->getDigits($digits);
            $divider = $this->getDivider($digits);
            $value = $value * $divider;
            $value = (float) (string) $value;
        }
        return new Money($value);
    }
}

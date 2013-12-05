<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @DI\Service("lighthouse.core.data_transformer.money_model")
 */
class MoneyModelTransformer implements DataTransformerInterface
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @DI\InjectParams({
     *      "numericFactory" = @DI\Inject("lighthouse.core.types.numeric.factory")
     * })
     * @param NumericFactory $numericFactory
     */
    public function __construct(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @param Money $value
     * @return string
     * @throws TransformationFailedException
     */
    public function transform($value)
    {
        if (null === $value) {
            $return = null;
        } elseif ($value instanceof Money) {
            if ($value->isNull()) {
                $return = null;
            } else {
                $return = $value->toString();
            }
        } else {
            throw new TransformationFailedException(
                'Value should be Money type object or null. ' . gettype($value) . ' given'
            );
        }
        return $return;
    }

    /**
     * @param mixed $value
     * @return Money
     */
    public function reverseTransform($value)
    {
        return $this->numericFactory->createMoney($value, true);
    }
}

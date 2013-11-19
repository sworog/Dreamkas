<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @DI\Service("lighthouse.core.data_transformer.quantity")
 */
class QuantityTransformer implements DataTransformerInterface
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
     * @param mixed $value
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @return mixed|void
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        } elseif ($value instanceof Quantity) {
            return $value->toString();
        }

        throw new TransformationFailedException(
            'Value should be Quantity type object or null. ' . gettype($value) . ' given'
        );
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        if ('' === $value || null === $value) {
            $value = null;
        }
        return $this->numericFactory->createQuantity($value);
    }
}

<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

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
     * @throws TransformationFailedException
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
     * @throws TransformationFailedException
     * @return Quantity|null
     */
    public function reverseTransform($value)
    {
        if ('' === $value || null === $value) {
            return null;
        } else {
            $value = str_replace(',', '.', (string) $value);
            if (!is_numeric($value)) {
                throw new TransformationFailedException('', 0, new UnexpectedTypeException($value, 'float'));
            }
            $quantity = $this->numericFactory->createQuantity($value);
            $quantity->setRaw($value);
            return $quantity;
        }
    }
}

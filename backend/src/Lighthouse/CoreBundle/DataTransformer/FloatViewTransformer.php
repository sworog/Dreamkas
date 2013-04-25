<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @DI\Service("lighthouse.core.data_transformer.float_view")
 */
class FloatViewTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        if (null !== $value) {
            $value = str_replace(',', '.', (string) $value);
        }

        if ('' !== $value && null !== $value) {
            if (!is_numeric($value)) {
                throw new TransformationFailedException('', 0, new UnexpectedTypeException($value, 'float'));
            }
        }

        return $value;
    }

}
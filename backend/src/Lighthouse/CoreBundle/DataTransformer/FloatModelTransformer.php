<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.data_transformer.float_model")
 */
class FloatModelTransformer implements DataTransformerInterface
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
        if (null !== $value && '' !== $value) {
            $value = (float) (string) $value;
        } else {
            $value = null;
        }

        return $value;
    }

}

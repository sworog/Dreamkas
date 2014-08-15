<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class EnumViewTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (is_array($value)) {
            return implode(',', $value);
        }

        throw new TransformationFailedException(
            '',
            0,
            new UnexpectedTypeException($value, 'array')
        );
    }

    /**
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('', 0, new UnexpectedTypeException($value, 'string'));
        }

        return $this->splitValues($value);
    }

    /**
     * @param string $value
     * @return array
     */
    protected function splitValues($value)
    {
        $values = explode(',', $value);
        $values = array_map('trim', $values);
        $values = array_filter(
            $values,
            function ($v) {
                return '' !== $v;
            }
        );
        return $values;
    }
}

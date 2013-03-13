<?php

namespace Lighthouse\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PriceViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function reverseTransform($value)
    {
        return str_replace(',', '.', (string) $value);
    }
}

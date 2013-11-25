<?php

namespace Lighthouse\CoreBundle\Tests\DataTransformer;

use Lighthouse\CoreBundle\DataTransformer\QuantityTransformer;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class QuantityTransformerTest extends TestCase
{
    /**
     * @return QuantityTransformer
     */
    protected function createTransformer()
    {
        $numberFactory = new NumericFactory(3, 2);
        return new QuantityTransformer($numberFactory);
    }

    /**
     * @param mixed $invalidValue
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @dataProvider invalidTransformProvider
     */
    public function testInvalidTransform($invalidValue)
    {
        $transformer = $this->createTransformer();
        $transformer->transform($invalidValue);
    }

    /**
     * @return array
     */
    public function invalidTransformProvider()
    {
        return array(
            array(2),
            array(Money::createFromNumeric('12.11', 2)),
        );
    }
}

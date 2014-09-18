<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\CompareObjectFixture;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompare;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MoneyCompareValidatorTest extends ConstraintTestCase
{
    /**
     * @dataProvider invalidFieldValueProvider
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Money"
     * @param $a
     * @param $b
     */
    public function testInvalidFieldValue($a, $b)
    {
        $this->doValidate($a, $b);
    }

    /**
     * @return array
     */
    public function invalidFieldValueProvider()
    {
        return array(
            array(10, new Money(1000)),
            array(new Money(1000), 10),
            array(new Money(1000), 'aaa'),
            array('aaa', new Money(1000)),
        );
    }

    /**
     * @param $minValue
     * @param $maxValue
     * @return ConstraintViolationListInterface
     */
    protected function doValidate($minValue, $maxValue)
    {
        $options = array(
            'minField' => 'fieldMin',
            'maxField' => 'fieldMax',
            'message' => 'message'
        );
        $constraint = new MoneyCompare($options);

        $object = new CompareObjectFixture();
        $object->fieldMin = $minValue;
        $object->fieldMax = $maxValue;

        return $this->getValidator()->validate($object, $constraint, null);
    }
}

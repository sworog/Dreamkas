<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\CompareObjectFixture;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompare;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyCompareValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class MoneyCompareValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var MoneyCompareValidatorTest
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $this->validator = new MoneyCompareValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    /**
     * @dataProvider invalidFieldValueProvider
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Money"
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

        $this->validator->validate($object, $constraint);
    }
}

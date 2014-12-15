<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Tests\Validator\Constraints\CompareObjectFixture;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\DatesCompare;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use DateTime;

class DatesCompareValidatorTest extends ConstraintTestCase
{
    /**
     * @dataProvider validValuesProvider
     * @param DateTime $orderDate
     * @param DateTime $createdDate
     */
    public function testValidValues(DateTime $orderDate = null, DateTime $createdDate = null)
    {
        $violations = $this->doValidate($orderDate, $createdDate);
        $this->assertCount(0, $violations);
    }

    /**
     * @return array
     */
    public function validValuesProvider()
    {
        return array(
            array(
                new \DateTime('2013-01-04'),
                new \DateTime('2013-01-03'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:03'),
                new \DateTime('2013-01-03 15:50:02'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                new \DateTime('2013-01-03 15:50:02'),
            ),
            array(
                null,
                new \DateTime('2013-01-03 15:50:02'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                null,
            ),
        );
    }

    /**
     * @dataProvider invalidValuesProvider
     * @param DateTime $orderDate
     * @param DateTime $createdDate
     */
    public function testInvalidValues(DateTime $orderDate, DateTime $createdDate)
    {
        $violations = $this->doValidate($orderDate, $createdDate);
        $this->assertCount(1, $violations);
    }

    /**
     * @return array
     */
    public function invalidValuesProvider()
    {
        return array(
            array(
                new \DateTime('2013-01-03'),
                new \DateTime('2013-01-04'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                new \DateTime('2013-01-03 15:50:03'),
            ),
        );
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Lighthouse\CoreBundle\Document\AbstractDocument"
     * @dataProvider unexpectedValueTypeProvider
     * @param mixed $value
     */
    public function testValueUnexpectedType($value)
    {
        $options = array(
            'minField' => 'orderDate',
            'maxField' => 'createdDate',
            'message' => 'message'
        );
        $constraint = new DatesCompare($options);

        $this->getValidator()->validate($value, $constraint, null);
    }

    /**
     * @return array
     */
    public function unexpectedValueTypeProvider()
    {
        return array(
            array(null),
            array(new \stdClass),
            array(array()),
            array(1),
            array(false),
            array("test")
        );
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "\DateTime"
     * @dataProvider fieldUnexpectedTypesProvider
     * @param mixed $orderDate
     * @param mixed $createdDate
     */
    public function testFieldUnexpectedType($orderDate, $createdDate)
    {
        $this->doValidate($orderDate, $createdDate);
    }

    /**
     * @return array
     */
    public function fieldUnexpectedTypesProvider()
    {
        return array(
            array(
                123,
                new \DateTime('2013-01-04'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                564564,
            ),
            array(
                "test",
                new \DateTime('2013-01-04'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                "test",
            ),
            array(
                "2013-01-04",
                new \DateTime('2013-01-04'),
            ),
            array(
                new \DateTime('2013-01-03 15:50:02'),
                "2013-01-03 15:50:02",
            ),
        );
    }

    /**
     * @param \DateTime $orderDate
     * @param \DateTime $createdDate
     * @return ConstraintViolationListInterface
     */
    protected function doValidate($orderDate = null, $createdDate = null)
    {
        $options = array(
            'minField' => 'createdDate',
            'maxField' => 'orderDate',
            'message' => 'message'
        );
        $constraint = new DatesCompare($options);

        $value = new CompareObjectFixture();
        $value->orderDate = $orderDate;
        $value->createdDate = $createdDate;

        return $this->getValidator()->validate($value, $constraint, null);
    }
}

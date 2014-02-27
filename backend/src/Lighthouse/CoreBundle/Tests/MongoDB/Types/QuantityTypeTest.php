<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\MongoDB\Types\QuantityType;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

class QuantityTypeTest extends TestCase
{
    /**
     * @var QuantityType
     */
    protected $type;

    protected function setUp()
    {
        Type::registerType(QuantityType::QUANTITY, QuantityType::getClassName());
        $this->type = Type::getType(QuantityType::QUANTITY);
    }

    /**
     * @dataProvider convertToPHPProvider
     * @param mixed $value
     * @param mixed $expected
     */
    public function testConvertToPHP($value, $expected)
    {
        $this->assertEquals($expected, $this->type->convertToPHP($value));
    }

    /**
     * @return array
     */
    public function convertToPHPProvider()
    {
        return array(
            'quantity' => array(
                array('count' => 1000, 'precision' => 2),
                new Quantity(1000, 2)
            ),
            'invalid array' => array(
                array('count' => 1000, 'digits' => 2),
                null,
            ),
            'null' => array(
                null,
                null
            )
        );
    }

    /**
     * @dataProvider convertToMongoProvider
     * @param mixed $value
     * @param mixed $expected
     */
    public function testConvertToMongo($value, $expected)
    {
        $this->assertEquals($expected, $this->type->convertToMongo($value));
    }

    /**
     * @return array
     */
    public function convertToMongoProvider()
    {
        return array(
            'quantity' => array(
                new Quantity(1000, 2),
                array('count' => 1000, 'precision' => 2),

            ),
            'invalid value' => array(
                array('count' => 1000, 'digits' => 2),
                null,
            ),
            'null' => array(
                null,
                null
            )
        );
    }
}

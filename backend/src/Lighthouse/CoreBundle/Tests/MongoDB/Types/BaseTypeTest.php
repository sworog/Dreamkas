<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use Lighthouse\CoreBundle\MongoDB\Types\BaseType;
use Lighthouse\CoreBundle\Test\TestCase;

class BaseTypeTest extends TestCase
{
    /**
     * @var BaseType
     */
    protected $type;

    protected function setUp()
    {
        if (!Type::hasType('base')) {
            Type::addType('base', BaseType::getClassName());
        }
        $this->type = Type::getType('base');
    }

    public function testClosureToPHP()
    {
        $this->assertSame(
            '$return = \Lighthouse\CoreBundle\MongoDB\Types\BaseType::convertToPhp($value);',
            $this->type->closureToPHP()
        );
    }

    public function testClosureToMongo()
    {
        $this->assertSame(
            '$return = \Lighthouse\CoreBundle\MongoDB\Types\BaseType::convertToMongo($value);',
            $this->type->closureToMongo()
        );
    }

    public function testConvertToPHPValue()
    {
        $value = new \stdClass();
        $this->assertSame($value, $this->type->convertToPHPValue($value));
    }

    public function testConvertToDatabaseValue()
    {
        $value = new \stdClass();
        $this->assertSame($value, $this->type->convertToDatabaseValue($value));
    }
}

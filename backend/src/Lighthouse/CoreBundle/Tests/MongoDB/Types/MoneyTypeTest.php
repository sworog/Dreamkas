<?php

namespace Lighthouse\CoreBundle\Tests\MongoDB\Types;

use Lighthouse\CoreBundle\MongoDB\Types\MoneyType;
use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Doctrine\ODM\MongoDB\Types\Type;

class MoneyTypeTest extends TestCase
{
    public function testType()
    {
        Type::registerType('money', MoneyType::getClassName());
        $moneyType = Type::getType('money');
        $this->assertInstanceOf(MoneyType::getClassName(), $moneyType);

        $money = new Money(1000);
        $dbValue = $moneyType->convertToDatabaseValue($money);
        $this->assertEquals(1000, $dbValue);

        $phpValue = $moneyType->convertToPHPValue(1000);
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Types\\Numeric\\Money', $phpValue);
        $this->assertEquals(1000, $phpValue->getCount());

        $this->assertContains('$return ', $moneyType->closureToPHP());
        $this->assertContains('$return ', $moneyType->closureToMongo());
    }
}

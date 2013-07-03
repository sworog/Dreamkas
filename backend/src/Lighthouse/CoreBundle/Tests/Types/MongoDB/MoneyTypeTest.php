<?php

namespace Lighthouse\CoreBundle\Tests\Types\MongoDB;

use Lighthouse\CoreBundle\Types\Money;
use Doctrine\ODM\MongoDB\Types\Type;

class MoneyTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $moneyTypeClassName = 'Lighthouse\\CoreBundle\\Types\\MongoDB\\MoneyType';

    public function testType()
    {
        Type::registerType('money', $this->moneyTypeClassName);
        $moneyType = Type::getType('money');
        $this->assertInstanceOf($this->moneyTypeClassName, $moneyType);

        $money = new Money(1000);
        $dbValue = $moneyType->convertToDatabaseValue($money);
        $this->assertEquals(1000, $dbValue);

        $phpValue = $moneyType->convertToPHPValue(1000);
        $this->assertInstanceOf('Lighthouse\CoreBundle\Types\Money', $phpValue);
        $this->assertEquals(1000, $phpValue->getCount());

        $this->assertContains('$return ', $moneyType->closureToPHP());
        $this->assertContains('$return ', $moneyType->closureToMongo());
    }
}


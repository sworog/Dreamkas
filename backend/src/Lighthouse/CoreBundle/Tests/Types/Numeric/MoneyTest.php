<?php

namespace Lighthouse\CoreBundle\Tests\Types\Numeric;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class MoneyTest extends TestCase
{
    public function testCreateNull()
    {
        $money = Money::createFromNumeric(null, 2);
        $this->assertTrue($money->isNull());
        $this->assertNull($money->getCount());
    }
}

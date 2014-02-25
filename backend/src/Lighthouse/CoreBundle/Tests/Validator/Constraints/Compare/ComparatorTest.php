<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\Compare;

use Lighthouse\CoreBundle\Test\TestCase;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparator;

class ComparatorTest extends TestCase
{
    /**
     * @dataProvider compareProvider
     * @param string $operator
     * @param int $a
     * @param int $b
     * @param bool $expectedResult
     */
    public function testCompare($operator, $a, $b, $expectedResult)
    {
        $comparator = new Comparator();
        $result = $comparator->compare($a, $b, $operator);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function compareProvider()
    {
        return array(
            'lt1' => array('lt', 1, 2, true),
            'lt2' => array('lt', 2, 2, false),
            'lt3' => array('lt', 2, 1, false),
            'gt1' => array('gt', 1, 2, false),
            'gt2' => array('gt', 2, 2, false),
            'gt3' => array('gt', 2, 1, true),
            'lte1' => array('lte', 1, 2, true),
            'lte2' => array('lte', 2, 2, true),
            'lte3' => array('lte', 2, 1, false),
            'gte1' => array('gte', 1, 2, false),
            'gte2' => array('gte', 2, 2, true),
            'gte3' => array('gte', 2, 1, true),
            'eq1' => array('eq', 1, 2, false),
            'eq2' => array('eq', 2, 2, true),
            'eq3' => array('eq', 2, 1, false),
            'neq1' => array('neq', 1, 2, true),
            'neq2' => array('neq', 2, 2, false),
            'neq3' => array('neq', 2, 1, true),
        );
    }
}

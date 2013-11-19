<?php

namespace Lighthouse\Store;

use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\TestCase;

class StoreTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetRoleByRelInvalidRel()
    {
        Store::getRoleByRel('invalid');
    }
}

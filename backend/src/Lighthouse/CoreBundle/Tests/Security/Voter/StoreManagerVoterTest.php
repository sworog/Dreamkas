<?php

namespace Lighthouse\CoreBundle\Tests\Security\Voter;

use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\Voter\StoreManagerVoter;
use Lighthouse\CoreBundle\Test\TestCase;

class StoreManagerVoterTest extends TestCase
{
    /**
     * @dataProvider supportsClassProvider
     * @param string $className
     * @param bool $expectedResult
     */
    public function testSupportsClass($className, $expectedResult)
    {
        $voter = new StoreManagerVoter();
        $this->assertEquals($expectedResult, $voter->supportsClass($className));
    }

    /**
     * @return array
     */
    public function supportsClassProvider()
    {
        return array(
            'user' => array(User::getClassName(), true),
            'dummy' => array('DummyClassName', true)
        );
    }

    /**
     * @dataProvider getRoleByAttributeProvider
     * @param string $attribute
     * @param string|null $expectedRole
     */
    public function testGetRoleByAttribute($attribute, $expectedRole)
    {
        $voter = new StoreManagerVoter();
        $this->assertSame($expectedRole, $voter->getRoleByAttribute($attribute));
    }

    /**
     * @return array
     */
    public function getRoleByAttributeProvider()
    {
        return array(
            StoreManagerVoter::ACL_DEPARTMENT_MANAGER => array(
                StoreManagerVoter::ACL_DEPARTMENT_MANAGER,
                User::ROLE_DEPARTMENT_MANAGER
            ),
            StoreManagerVoter::ACL_STORE_MANAGER => array(
                StoreManagerVoter::ACL_STORE_MANAGER,
                User::ROLE_STORE_MANAGER
            ),
            'dummy' => array(
                'dummy',
                null
            )
        );
    }
}

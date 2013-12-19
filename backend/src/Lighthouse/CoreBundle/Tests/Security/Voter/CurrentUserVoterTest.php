<?php

namespace Lighthouse\CoreBundle\Tests\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Security\Voter\CurrentUserVoter;
use Lighthouse\CoreBundle\Test\TestCase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class CurrentUserVoterTest extends TestCase
{
    public function testSupportClass()
    {
        $voter = new CurrentUserVoter();
        $this->assertTrue($voter->supportsClass('User'));
        $this->assertTrue($voter->supportsClass('Always returns true'));
    }

    public function testSupportsAttribute()
    {
        $voter = new CurrentUserVoter();
        $this->assertTrue($voter->supportsAttribute(CurrentUserVoter::ACL_CURRENT_USER));
        $this->assertFalse($voter->supportsAttribute('Some other attribute'));
    }

    /**
     * @return TokenInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTokenMock()
    {
        /* @var  $tokenMock */
        return $this->getMock('Symfony\\Component\\Security\\Core\\Authentication\\Token\\TokenInterface');
    }

    public function testVoteAccessGranted()
    {
        $user = new User();

        $tokenMock = $this->getTokenMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $voter = new CurrentUserVoter();
        $result = $voter->vote($tokenMock, $user, array(CurrentUserVoter::ACL_CURRENT_USER));
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testVoteAccessDenied()
    {
        $user1 = new User();
        $user2 = new User();

        $tokenMock = $this->getTokenMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user2));

        $voter = new CurrentUserVoter();
        $result = $voter->vote($tokenMock, $user1, array(CurrentUserVoter::ACL_CURRENT_USER));
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testVoteAccessAbstainObjectNotUser()
    {
        $user = new User();

        $tokenMock = $this->getTokenMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $object = new \stdClass();
        $voter = new CurrentUserVoter();
        $result = $voter->vote($tokenMock, $object, array(CurrentUserVoter::ACL_CURRENT_USER));
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testVoteAccessAbstainNotSupportedAttribute()
    {
        $user = new User();

        $tokenMock = $this->getTokenMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $voter = new CurrentUserVoter();
        $result = $voter->vote($tokenMock, $user, array('some other attribute'));
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testVoteAccessAbstainEmptyAttributes()
    {
        $user = new User();

        $tokenMock = $this->getTokenMock();
        $tokenMock
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));

        $voter = new CurrentUserVoter();
        $result = $voter->vote($tokenMock, $user, array());
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }
}

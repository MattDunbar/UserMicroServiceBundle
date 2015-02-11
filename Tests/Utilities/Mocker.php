<?php
namespace MattDunbar\UserMicroServiceBundle\Tests\Utilities;

class Mocker extends \PHPUnit_Framework_TestCase
{
    /**
     * Create mock user object.
     *
     * @param integer|bool $userId
     * @param string|bool $email
     * @return \MattDunbar\UserMicroServiceBundle\Entity\User
     */
    function mockUser($userId = false, $email = false) {
        $user = $this->getMock('\MattDunbar\UserMicroServiceBundle\Entity\User');
        if($userId) {
            $user->expects($this->once())
                ->method('getId')
                ->will($this->returnValue($userId));
        }
        if($email) {
            $user->expects($this->once())
                ->method('getEmail')
                ->will($this->returnValue($email));
        }
        return $user;
    }

    /**
     * Create mock token object.
     *
     * @param \MattDunbar\UserMicroServiceBundle\Entity\User|bool $user
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    function mockToken($user = false) {
        $token = $this->getMock('\Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        if($user) {
            $token->expects($this->once())
                ->method('getUser')
                ->will($this->returnValue($user));
        }
        return $token;
    }

    /**
     * Create mock security context object.
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface|bool $token
     * @return \Symfony\Component\Security\Core\SecurityContextInterface
     */
    function mockSecurityContext($token = false) {
        $securityContext = $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();
        if($token) {
            $securityContext->expects($this->once())
                ->method('getToken')
                ->will($this->returnValue($token));
        }
        return $securityContext;
    }
}
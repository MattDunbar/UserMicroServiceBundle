<?php

namespace MattDunbar\UserMicroServiceBundle\Tests\Controller;
use MattDunbar\UserMicroServiceBundle\Controller\AuthenticationController;
use MattDunbar\UserMicroServiceBundle\Tests\Utilities\Mocker;

class AuthenticationControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \MattDunbar\UserMicroServiceBundle\Tests\Utilities\Mocker */
    protected $mocker;

    protected function setUp()
    {
        $this->mocker = new Mocker;
    }

    /**
     * Tests check action.
     */
    public function testCheckAction()
    {
        $sampleUserId = 123;
        $sampleUserEmail = 'test@example.com';

        $userMock = $this->mocker->mockUser($sampleUserId, $sampleUserEmail);
        $tokenMock = $this->mocker->mockToken($userMock);
        $securityContextMock = $this->mocker->mockSecurityContext($tokenMock);
        $userManagerMock = $this->getMock('\FOS\UserBundle\Model\UserManagerInterface');

        $authenticationController = new AuthenticationController($userManagerMock, $securityContextMock);
        $checkAction = $authenticationController->checkAction();

        $jsonContent = $checkAction->getContent();
        $content = json_decode($jsonContent);

        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $checkAction);
        $this->assertEquals(200, $checkAction->getStatusCode());
        $this->assertEquals($sampleUserId, $content->user_id);
        $this->assertEquals($sampleUserEmail, $content->email);
    }
}

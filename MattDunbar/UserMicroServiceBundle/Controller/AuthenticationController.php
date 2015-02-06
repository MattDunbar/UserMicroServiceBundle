<?php

namespace MattDunbar\UserMicroServiceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use FOS\UserBundle\Model\UserManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Class AuthenticationController
 * @package MattDunbar\UserMicroServiceBundle\Controller
 * @Route("/auth", service="matt_dunbar_user_micro_service.authentication_controller")
 */
class AuthenticationController
{
    /** @var $userManager UserManagerInterface */
    protected $userManager;
    /** @var $securityContext SecurityContextInterface */
    protected $securityContext;

    /**
     * @param UserManagerInterface $userManager
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(UserManagerInterface $userManager, SecurityContextInterface $securityContext) {
        $this->userManager = $userManager;
        $this->securityContext = $securityContext;
    }

    /**
     * Returns the authenticated user's User ID.
     *
     * @Route("/check")
     * @Method("GET")
     * @Cache(expires="+30 days", public=true)
     * @return JsonResponse
     */
    public function checkAction()
    {
        /** @var $user \Uecode\Bundle\ApiKeyBundle\Entity\ApiKeyUser */
        $user = $this->securityContext->getToken()->getUser();
        return new JsonResponse(
            array(
                'user_id' => $user->getId(),
                'email' => $user->getEmail()
            )
        );
    }

    /**
     * Authenticate the user based on username and password then return their User ID and API Token.
     *
     * @Route("/login")
     * @Method("POST")
     * @param Request $request
     * @throws AuthenticationException
     * @return JsonResponse
     */
    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            if ($error instanceof AuthenticationException) {
                throw new AuthenticationException("Authentication failed.");
            }
        }
        /** @var $user \Uecode\Bundle\ApiKeyBundle\Entity\ApiKeyUser */
        $user = $this->securityContext->getToken()->getUser();
        return new JsonResponse(
            array(
                'user_id' => $user->getId(),
                'api_token' => $user->getApiKey()
            )
        );
    }


    /**
     * Register new user then return their Api Token and User ID
     *
     * @Route("/register")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {
        /** @var $user \Uecode\Bundle\ApiKeyBundle\Entity\ApiKeyUser */
        $user = $this->userManager->createUser();
        $user->setEnabled(true);
        if($request->request->has('email')) {
            $email = $request->request->get('email');
            $user->setEmail($email);
            $user->setUsername($email);
        }
        if($request->request->has('password')) {
            $password = $request->request->get('password');
            $user->setPassword($password);
        }
        $this->userManager->updateUser($user);
        return new JsonResponse(array('user_id' => $user->getId(), 'api_token' => $user->getApiKey()));
    }
}

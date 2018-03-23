<?php

namespace App\Controller;

use App\Model\User;

use Qubit\Bundle\UtilsBundle\Context\Context;
use Qubit\Bundle\UtilsBundle\Annotations\ContextCheck;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class UserController
 * @package App\Controller
 *
 * @Route("/v1/api/auth")
 *
 */
class AuthController extends FOSRestController
{

    /**
     * @Post("/login")
     * @ContextCheck(type="INIT")
     *
     * @RequestParam(name="username",  strict=true,  nullable=false, description="Username")
     * @RequestParam(name="password", requirements="\w+", strict=true,  nullable=false, description="Password")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit Context"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Login a user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="Auth")
     * @param ParamFetcher $paramFetcher
     * @param $stopwatch
     * @return JsonResponse
     */
    public function loginAction(ParamFetcher $paramFetcher, $stopwatch)
    {
        $stopwatch->start('AuthController::login');
//TODO Agregar la expresion regular del username
        $username = $userId = $paramFetcher->get('username');
        $password = $userId = $paramFetcher->get('password');

        /** @var User $user */
        $user = $this->get('App\Service\UserService')->get(['username' => $username]);
        if ($user) {
            $this->get('App\Service\AuthService')->login($user->getId(), $password);
        }

        /** @var Context $context */
        $context = $this->get('qubit.context.manager')->getContext();
        $context->setUserId($user->getId());
        $context->setUsername($user->getUsername());
        $context->setAccountId($user->getAccount());

//        $context->setUserRoles($user->getRoles());
        $context->setUserRegion($user->getRegion());

        $stopwatch->stop('AuthController::login');

        return $this->json($user);
    }

    /**
     * @Post("/autologin")
     * @ContextCheck(type="INIT")
     *
     * @RequestParam(name="hash",  strict=true,  nullable=false, description="Autologin Hash")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit Context"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Autologin a user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="Auth")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     */
    public function autologinAction(ParamFetcher $paramFetcher)
    {
        $hash = $paramFetcher->get('hash');

        $auth = $this->get('App\Service\AuthService')->autoLogin($hash);

        $user = null;
        if ($auth) {
            $user = $this->get('App\Service\UserService')->get($auth);
        }

//        $context = $this->get('qubit.context.manager')->getContext();
        $context = Context::getInstance();
        $context->setUserId($user->getId());
        $context->setUsername($user->getUsername());
        $context->setAccountId($user->getAccount());
//        $context->setUsername($user->getUsername());
//        $context->setUserRoles($user->getRoles());
        $context->setUserRegion($user->getRegion());

        return $this->json($user);
    }

    /**
     * @Post("/changePassword")
     * @ContextCheck(type="AUTH")
     *
     * @RequestParam(name="id", strict=true,  nullable=false, description="User id")
     * @RequestParam(name="current",  strict=true,  nullable=false, description="Current Password")
     * @RequestParam(name="new",  strict=true,  nullable=false, description="New Password")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit Context"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Change user password",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="Auth")
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     */
    public function changePasswordAction(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('id');
        $currentPassword = $paramFetcher->get('current');
        $newPassword = $paramFetcher->get('new');

        $user = null;
        if ($this->get('App\Service\AuthService')->changePassword($userId, $currentPassword, $newPassword)) {
            $user = $this->get('App\Service\UserService')->get($userId);
        }

        return $this->json($user);
    }

    /**
     * @Post("/resetPassword")
     * @ContextCheck(type="AUTH")
     *
     * @RequestParam(name="id", strict=true,  nullable=false, description="User id")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit Context"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Reset user password",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="Auth")
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     */
    public function resetPasswordAction(ParamFetcher $paramFetcher)
    {
//TODO hacer
        $userId = $paramFetcher->get('id');

        $user = null;
        if ($this->get('App\Service\AuthService')->resetPassword($userId)) {
            $user = $this->get('App\Service\UserService')->get($userId);
        }

        return $this->json($user);
    }


}

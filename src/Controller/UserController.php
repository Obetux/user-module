<?php

namespace App\Controller;

use App\Model\User;
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
 * @Route("/v1/api/user")
 *
 */
class UserController extends FOSRestController
{

    /**
     * @Post("/check")
     * @ContextCheck(type="INIT")
     *
     * @RequestParam(name="username", strict=true,  nullable=false, description="Username")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit Context"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Check if the username is available"
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @SWG\Tag(name="User")
     * @param ParamFetcher $paramFetcher
     * @return JsonResponse
     */
    public function checkAction(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('username');

        $user = $this->get('App\Service\UserService')->check($userId);

        return $this->json($user);
    }

    /**
     * @Get("/get")
     * @ContextCheck(type="AUTH")
     *
     * @QueryParam(name="id", strict=true,  nullable=false, description="User id")
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
     *     description="Retrieve a user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="User")
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     */
    public function getAction(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('id');

        $user = $this->get('App\Service\UserService')->get(['userId' => $userId]);

        return $this->json($user);
    }

    /**
     * @Post("/new")
     * @ContextCheck(type="INIT")
     *
     * @RequestParam(name="firstName", strict=true,  nullable=false, description="User firstName")
     * @RequestParam(name="lastName", strict=true,  nullable=false, description="User lastName")
     * @RequestParam(name="username", strict=true,  nullable=false, description="User username")
     * @RequestParam(name="email", strict=false,  nullable=true, description="User email")
     * @RequestParam(name="phone", strict=false,  nullable=true, description="User phone")
     * @RequestParam(name="password", strict=true,  nullable=false, description="User password")
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
     *     description="Create new user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="User")
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     */
    public function newAction(ParamFetcher $paramFetcher)
    {
//TODO hacer
//        $params = [];
        $params = [
            'firstName' => $paramFetcher->get('firstName') ,
            'lastName' => $paramFetcher->get('lastName') ,
            'username' => $paramFetcher->get('username') ,
            'email' => $paramFetcher->get('email') ,
            'phone' => $paramFetcher->get('phone') ,
            'password' => $paramFetcher->get('password') ,

        ];
        /** @var User $user */
        $user = $this->get('App\Service\UserService')->new($params);

        if ($user) {
            $this->get('App\Service\AuthService')->new($user->getId(), $params['password']);
            $this->get('App\Service\AuthService')->login($user->getId(), $params['password']);
        }

        $context = $this->get('qubit.context.manager')->getContext();
        $context->setUserId($user->getId());
        $context->setUsername($user->getUsername());
//        $context->setUserRoles($user->getRoles());
        $context->setUserRegion($user->getRegion());

        return $this->json($user);
    }

    /**
     * @Post("/modify")
     * @ContextCheck(type="AUTH")
     *
     * @QueryParam(name="id", strict=true,  nullable=false, description="User id")
     * @RequestParam(name="firstName", strict=false,  nullable=true, description="User firstName")
     * @RequestParam(name="lastName", strict=false,  nullable=true, description="User lastName")
     * @RequestParam(name="username", strict=false,  nullable=true, description="User username")
     * @RequestParam(name="email", strict=false,  nullable=true, description="User email")
     * @RequestParam(name="phone", strict=false,  nullable=true, description="User phone")
     *
     * @SWG\Parameter(
     *     name="x-context",
     *     in="header",
     *     type="string",
     *     required=true,
     *     description="Qubit ContextC"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Modify a user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="User")
     * @param ParamFetcher $paramFetcher
     * @return JsonResponse
     */
    public function modifyAction(ParamFetcher $paramFetcher)
    {
//TODO do modify
        $userId = $paramFetcher->get('id');
        $params = [
            'firstName' => $paramFetcher->get('firstName') ,
            'lastName' => $paramFetcher->get('lastName') ,
            'username' => $paramFetcher->get('username') ,
            'email' => $paramFetcher->get('email') ,
            'phone' => $paramFetcher->get('phone') ,
        ];

        $user = $this->get('App\Service\UserService')->modify($userId, $params);

        return $this->json($user);
    }
}

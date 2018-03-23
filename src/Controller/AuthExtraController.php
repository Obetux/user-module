<?php

namespace App\Controller;

use Qubit\Bundle\UtilsBundle\Annotations\ContextCheck;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class AuthExtraController
 * @package App\Controller
 *
 * @Route("/v1/api/auth/extra")
 *
 */
class AuthExtraController extends FOSRestController
{
    /**
     * @Get("/devices")
     * @ContextCheck(type="AUTH")
     *
     * @QueryParam(name="userId", strict=true,  nullable=false, description="User id")
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
     *     description="Return a list of devices linked to user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @SWG\Tag(name="Auth")
     */
    public function getLinkedDevicesAction(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('userId');

        $user = $this->get('App\Service\AuthExtraService')->getLinkedDevices($userId);

        return $this->json($user);
    }

    /**
     * @Post("/device/disable")
     * @ContextCheck(type="AUTH")
     *
     * @RequestParam(name="userId", strict=true,  nullable=false, description="User id")
     * @RequestParam(name="deviceId", strict=true,  nullable=false, description="Device id")
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
     *     description="Return a list of devices linked to user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @SWG\Tag(name="Auth")
     */
    public function disableDevice(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('userId');
        $deviceId = $paramFetcher->get('deviceId');

        $this->get('App\Service\AuthExtraService')->disableDevice($userId, $deviceId);
        $user = $this->get('App\Service\AuthExtraService')->getLinkedDevices($userId);

        return $this->json($user);
    }

    /**
     * @Get("/loginHistory")
     * @ContextCheck(type="AUTH")
     *
     * @QueryParam(name="userId", strict=true,  nullable=false, description="User id")
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
     *     description="Return user login history"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User not found"
     * )
     * @SWG\Tag(name="Auth")
     */
    public function getLoginHistoryAction(ParamFetcher $paramFetcher)
    {
        $userId = $paramFetcher->get('userId');

        $history = $this->get('App\Service\AuthExtraService')->getLoginHistory($userId);

        return $this->json($history);
    }
}

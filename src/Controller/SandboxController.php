<?php

namespace App\Controller;

use Qubit\Bundle\UtilsBundle\Context\Context;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Swagger\Annotations as SWG;

/**
 * Class SandboxController
 *
 * @package App\Controller
 *
 *
 */
class SandboxController extends Controller
{
    /**
     * @Get("/sandbox")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Generate a sandbox context"
     * )
     *
     * @SWG\Tag(name="Sandbox")
     */
    public function index()
    {
        if ($this->get('kernel')->getEnvironment() !== "dev") {
            throw new NotFoundHttpException();
        }
        $context = Context::getInstance();
        $context->setVertical('Qubit');
        $context->setService('Qubit');
        $context->setRegion('AR');
        $context->setIpAddress('95.65.125.32');
        $context->setUserAgent('MOZILLA');

        return $this->json(['ok' => true]);
    }
}

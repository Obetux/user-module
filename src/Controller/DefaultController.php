<?php

namespace App\Controller;

use Qubit\Bundle\UtilsBundle\Context\Context;
use Qubit\Bundle\UtilsBundle\Factory\CacheManagerFactory;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->json(['data' => 123]);
        // replace this example code with whatever you need

    }


    /**
     * @Route("/api", name="api")
     * @Method({"GET"})
     */
    public function apiAction(Request $request)
    {
        $response = new JsonResponse(array('data' => 123));
        return $response;
    }

    /**
     * @Route("/test", name="test")
     * @Method({"GET"})
     */
    public function testAction()
    {

        $context = Context::getInstance();
        $context->setService('Qubit');
        $context->setVertical('Qubit');
        $context->setRegion('CL');
        $context->setUserId(9);

        $contextHan = $this->get('Qubit\Bundle\UtilsBundle\Context\ContextManager');
        $contextHan->setContext($context);
        $jwt = $contextHan->getToken();

        return $this->json(array('token' => $jwt));
    }

    /**
     * @Route("/cache", name="cache")
     * @Method({"GET"})
     */
    public function cacheAction()
    {

        $user = $this->get('App\Service\UserService')->check('asd');
        var_dump($user );exit;
        $cache = $this->get('App\Service\CacheManagerFactory');
        $cache = $cache->cacheManager('app.cache');
//        $cache = CacheManagerFactory::cache('app.cache');
        dump($cache);exit;
//        $latestNews = $cache->getItem('latest_news');
        $cache->set('stats.num_products', 666);

        if ($cache->has('stats.num_products')) {
            // ... item does not exists in the cache
            echo "TIENE";
        }

        $numProducts = $cache->get('stats.num_products');
var_dump($numProducts);
exit;
//        $cache->delete('stats.num_products');

    }

    /**
     * @Route("/uuid", name="uuid")
     * @Method({"GET"})
     */
    public function uuidAction()
    {
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, 'qubit');
        var_dump($uuid->toString());
        exit;
    }
}

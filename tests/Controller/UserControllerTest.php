<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 04/12/2017
 * Time: 9:55
 */

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function checkProfiler($profile)
    {
//error_log(print_r($profile->getCollector('logger')->countDeprecations()));exit;
//var_dump(get_class_methods($profile->getCollector('logger')));exit;
//var_dump($profile);exit;
        // Check that the profiler is enabled
        if ($profile) {
            // check the number of requests
            $this->assertLessThan(
                5,
                $profile->getCollector('db')->getQueryCount(),
                sprintf(
                    'Checks that query count is less than 5 (token %s)',
                    $profile->getToken()
                )
            );
            $spentTime = 450;
            // check the time spent in the framework
            $this->assertLessThan(
                $spentTime,
                $profile->getCollector('time')->getDuration(),
                sprintf(
                    'Checks that request time is less than '.$spentTime.' (token %s)',
                    $profile->getToken()
                )
            );

            $this->assertEquals(
                0,
                $profile->getCollector('logger')->countDeprecations(),
                sprintf(
                    'Checks Deprecations (token %s)',
                    $profile->getToken()
                )
            );
        }
    }

    public function testCheckAction()
    {
        $client = static::createClient();

        // Enable the profiler for the next request
        // (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $client->request('GET', '/v1/api/user/check?username=testlogin@qubit.tv');

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

        // Assert that the "x-content" header exist
        $this->assertTrue(
            $client->getResponse()->headers->has(
                'x-context'
            ),
            'the "x-context" header not exist' // optional message shown on failure
        );



//var_dump($client->getResponse());exit;
        // ... write some assertions about the Response
// Assert that the response status code is 2xx
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is not 2xx');
// Assert that the response status code is 404
//        $this->assertTrue($client->getResponse()->isNotFound());
// Assert a specific 200 status code
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );

        $this->checkProfiler($client->getProfile());
    }

    public function testCheckActionUserNotFound()
    {
        $client = static::createClient();

        // Enable the profiler for the next request
        // (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $client->request('GET', '/v1/api/user/check?username=SARLANGA');

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

        // Assert that the "x-content" header exist
        $this->assertTrue(
            $client->getResponse()->headers->has(
                'x-context'
            ),
            'the "x-context" header not exist' // optional message shown on failure
        );

// Assert that the response status code is 404
        $this->assertTrue($client->getResponse()->isNotFound());
// Assert a specific 200 status code
        $this->assertEquals(
            404, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );

        $this->checkProfiler($client->getProfile());
    }

    public function testLoginAction()
    {
        $client = static::createClient();

        $client->enableProfiler();

        $client->request('GET', '/v1/api/user/login?username=testlogin@qubit.tv&password=qubit');

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

        // Assert that the "x-content" header exist
        $this->assertTrue(
            $client->getResponse()->headers->has(
                'x-context'
            ),
            'the "x-context" header not exist' // optional message shown on failure
        );

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is not 2xx');

        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );

        $this->checkProfiler($client->getProfile());
    }
}
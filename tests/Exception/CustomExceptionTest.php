<?php

namespace App\Tests\Exception;

use App\Exception\CustomException;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheManagerFactoryTest
 * @package App\Tests\Service
 */
class CustomExceptionTest extends TestCase
{
    public function testStatusCode()
    {
        $excep = new CustomException();
        $excep->setStatusCode(400);

        $this->assertEquals(400, $excep->getStatusCode());
    }

}

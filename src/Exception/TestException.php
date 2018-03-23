<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class TestException extends CustomException
{
    protected $code = 1670;
    protected $message = "Test mensaje";
    protected $statusCode = "404";
}

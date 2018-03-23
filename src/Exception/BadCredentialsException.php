<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class BadCredentialsException extends CustomException
{
    protected $code = 1011;
    protected $message = "Bad Credentials";
    protected $statusCode = "404";
}

<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class NewUserException extends CustomException
{
    protected $code = 1020;
    protected $message = "An error occurred when try to create a new user.";
    protected $statusCode = "500";
}

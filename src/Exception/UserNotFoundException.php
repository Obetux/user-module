<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class UserNotFoundException extends CustomException
{
    protected $code = 1010;
    protected $message = "User not found";
    protected $statusCode = "404";

}


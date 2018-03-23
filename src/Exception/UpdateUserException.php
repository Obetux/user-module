<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class UpdateUserException extends CustomException
{
    protected $code = 1021;
    protected $message = "An error occurred when try to update a user.";
    protected $statusCode = "500";
}
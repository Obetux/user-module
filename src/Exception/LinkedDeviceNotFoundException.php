<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class LinkedDeviceNotFoundException extends CustomException
{
    protected $code = 1030;
    protected $message = "Linked Device Not Found";
    protected $statusCode = "404";
}

<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class DeviceNotEnableException extends CustomException
{
    protected $code = 1013;
    protected $message = "Device Not Enable";
    protected $statusCode = "404";
}

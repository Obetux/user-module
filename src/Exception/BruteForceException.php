<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class BruteForceException extends CustomException
{
    protected $code = 1012;
    protected $message = "BruteForce";
    protected $statusCode = "500";
}

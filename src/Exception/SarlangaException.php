<?php

namespace App\Exception;

use Qubit\Bundle\UtilsBundle\Exception\CustomException;

/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 31/10/2017
 * Time: 11:33
 */
class SarlangaException extends CustomException
{
    protected $code = 1450;
    protected $message = "Sarlanga mensaje";
    protected $statusCode = "403";
}

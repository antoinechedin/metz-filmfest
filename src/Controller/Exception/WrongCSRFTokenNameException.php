<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 11/06/2018
 * Time: 20:58
 */

namespace App\Controller\Exception;


use Throwable;

class WrongCSRFTokenNameException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = "The provided CSRF token name isn't a string";
        $code = 500;

        parent::__construct($message, $code, $previous);
    }

}
<?php

namespace gift\app\services\utils;

use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class CsrfService
{
    /**
     * @throws ExceptionTokenGenerate
     */
    public static function generate():string
    {
        try {
            $token = random_bytes(64);
        } catch (Exception $e) {
            throw new ExceptionTokenGenerate();
        }
        $_SESSION['csrf'] = $token;
        return $token;
    }

    /**
     * @throws ExceptionTokenVerify
     */
    public static function check($token):void
    {
        $sessionToken = $_SESSION['csrf'];
        if (!$token !== $sessionToken) {
            unset($_SESSION['csrf']);
            throw new ExceptionTokenVerify();
        }
    }
}
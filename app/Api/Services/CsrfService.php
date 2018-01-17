<?php

namespace App\Api\Services;

use App\Api\Contracts\HttpContract;

class CsrfService implements HttpContract
{
    private $token_length = 20;

    public function __construct()
    {
        isset($_SESSION) or session_start();
    }

    public function update($token_key = 'XSRF-TOKEN')
    {
        $_SESSION[$token_key] = str_random($token_length);
        setcookie($token_key, $_SESSION[$token_key]);
    }

    public function check($token_key = 'XSRF-TOKEN')
    {
        return $_SESSION[$token_key] === $_COOKIE[$token_key];
    }
}

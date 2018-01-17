<?php

namespace App\Api\Services;

use App\Api\Contracts\CsrfContract;

class CsrfService implements CsrfContract
{
    private $token_length = 20;

    public function __construct()
    {
        isset($_SESSION) or session_start();
    }

    public function update($token_key = 'XSRF-TOKEN')
    {
        $_SESSION[$token_key] = str_random($this->token_length);
        setcookie($token_key, $_SESSION[$token_key]);
    }

    public function check($token_key = 'XSRF-TOKEN')
    {
        if (!isset($_SESSION[$token_key], $_COOKIE[$token_key])) {
            return false;
        }
        return $_SESSION[$token_key] === $_COOKIE[$token_key];
    }
}

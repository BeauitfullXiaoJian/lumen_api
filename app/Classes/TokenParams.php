<?php
namespace App\Classes;

class TokenParams
{
    private static $tokenParams;

    public static function setParams($tokenParams)
    {
        TokenParams::$tokenParams = $tokenParams;
    }

    public static function getParams()
    {
        return TokenParams::$tokenParams;
    }

}

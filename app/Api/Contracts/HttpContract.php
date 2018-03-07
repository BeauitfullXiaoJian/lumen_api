<?php

namespace App\Api\Contracts;

interface HttpContract
{

    // send a simple http get request
    public function get($url, $params = []);

    // try decode respone to json array,return result:boolean
    public function responsetoJson(&$json);
}

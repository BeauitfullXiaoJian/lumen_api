<?php

namespace App\Api\Services;

use App\Api\Contracts\HttpContract;

class HttpService implements HttpContract
{
    private $response;

    public function __construct()
    {
    }

    public function get($url, $params = [])
    {
        $curl = curl_init($url . '?' . http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $this->response = curl_exec($curl);
        curl_close($curl);
        return $this->response;
    }

    public function responsetoJson(&$json)
    {
        if (!isset($this->response) || empty($this->response)) {
            return false;
        }
        $json = json_decode($this->response, true);
        if (gettype($json) !== 'array' || count($json) <= 0) {
            return false;
        }
        return true;
    }
}

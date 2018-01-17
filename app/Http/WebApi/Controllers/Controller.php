<?php

/**
 * Created by PhpStorm.
 * User: zhao
 * Date: 17-11-17
 * Time: 上午10:34
 */

namespace App\Http\WebApi\Controllers;

use App\Api\Contracts\ApiContract;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $api;

    public function __construct(ApiContract $api)
    {
        $this->api = $api;
    }

}
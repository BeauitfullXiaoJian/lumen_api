<?php

/**
 * @author xiaojian
 * @file Controller.php
 * @info 基础控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use App\Api\Contracts\ApiContract;
use App\Api\Contracts\FileContract;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $api;

    public $file;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $api, FileContract $file)
    {
        $this->api = $api;
        $this->file = $file;
    }
}

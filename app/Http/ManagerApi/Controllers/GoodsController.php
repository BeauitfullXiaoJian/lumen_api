<?php

/**
 * @author xiaojian
 * @file AdminController.php
 * @info 系统账号控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use App\Http\ManagerApi\Controllers\Controller;

class GoodsController extends Controller
{
    /**
     * @name   上传图片
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息，上传结果]
     * @tdo    角色id没有进行校验
     */

    public function uploadGoods()
    {
        $url = $this->file->saveImageTo('thumb', 'upload');
        if ($this->file->isError($url)) {
            return $this->api->error('图片上传失败～');
        }
        return $url;
    }
}

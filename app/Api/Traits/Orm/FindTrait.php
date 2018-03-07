<?php

/**
 * @file:    FindTrait.php
 * @author:  xiaojian
 * @date:    2017-08-01
 * @exp:     提供find方法的拓展
 */

namespace App\Api\Traits\Orm;

trait FindTrait
{
    public function findBy($name, $value)
    {
        return $this->where($name,$value)->first();
    }
}

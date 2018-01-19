<?php

/**
 * @file:    FindTrait.php
 * @author:  xiaojian
 * @date:    2017-08-01
 * @exp:     提供find方法的拓展
 */

namespace App\Api\Traits\Orm;

trait BaseTrait
{
    public function trySave(array $attributes = [], array $ignores = [])
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($ignores as $ignore) {
            unset($attributes[$ignore]);
        }
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
        return $this->save();
    }
}

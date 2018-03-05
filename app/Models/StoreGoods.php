<?php

namespace App\Models;

use App\Api\Traits\Orm\SearchTrait;
use Illuminate\Database\Eloquent\Model;

class StoreGoods extends Model
{
    use SearchTrait;
    protected $table = 'store_goods';

    public function type()
    {
        return $this->hasOne('App\Models\StoreGoodsType', 'id', 'type')->withDefault([
            'name' => '未知种类',
        ]);
    }
}

<?php

namespace App\Models;

use App\Api\Traits\Orm\DataSortTrait;
use Illuminate\Database\Eloquent\Model;

class StoreGoodsType extends Model
{
    use DataSortTrait;

    protected $table = 'store_goods_type';
}

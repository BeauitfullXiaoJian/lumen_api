<?php

namespace App\Models;

use App\Api\Traits\Orm\SearchTrait;
use Illuminate\Database\Eloquent\Model;

class StoreGoods extends Model
{
    use SearchTrait;
    protected $table = 'store_goods';
}

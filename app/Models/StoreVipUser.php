<?php

namespace App\Models;

use App\Api\Traits\Orm\SearchTrait;
use App\Api\Traits\Orm\BaseTrait;

use Illuminate\Database\Eloquent\Model;

class StoreVipUser extends Model
{
    use SearchTrait, BaseTrait;
    protected $table = 'store_vip_user';
}

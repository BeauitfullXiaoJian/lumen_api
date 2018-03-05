<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Api\Traits\Orm\SearchTrait;

class AccessRole extends Model
{

    use SearchTrait;

    protected $table='access_user_role';

    // 关闭了时间戳
    public $timestamps = false;

}

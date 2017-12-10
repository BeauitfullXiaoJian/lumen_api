<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Api\Traits\Orm\SearchTrait;
use App\Api\Traits\Orm\FindTrait;

class AccessUser extends Model
{

    use SearchTrait,FindTrait;

    protected $table='access_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = [
        'created_time',
        'updated_time',
        'is_active'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];
}

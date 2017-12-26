<?php

namespace App\Models;

use App\Api\Traits\Orm\FindTrait;
use App\Api\Traits\Orm\SearchTrait;
use Illuminate\Database\Eloquent\Model;

class AccessUser extends Model
{

    use SearchTrait, FindTrait;

    protected $table = 'access_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'created_time',
        'updated_time',
        'is_active',
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

    public function role()
    {
        return $this->hasOne('App\Models\AccessRole', 'id', 'role')->withDefault([
            'name' => '未知年级',
        ]);
    }

}

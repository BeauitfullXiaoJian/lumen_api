<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLogin extends Model
{

    protected $table='access_login';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = [
        'token',
        'created_time',
        'updated_time'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}

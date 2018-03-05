<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPermission extends Model
{

    protected $table='access_permission';

    protected $fillable = ['id','name','description','key'];

    public $timestamps = false;

}

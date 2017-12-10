<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessPermissionModel extends Model
{

    protected $table = 'access_permission_model';

    protected $fillable = ['id', 'name'];

    public $timestamps = false;   

}

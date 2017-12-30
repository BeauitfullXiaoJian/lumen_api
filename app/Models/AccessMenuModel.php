<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Api\Traits\Orm\DataSortTrait;

class AccessMenuModel extends Model
{

    use DataSortTrait;

    protected $table = 'access_menu_model';

    public $timestamps = false;

}

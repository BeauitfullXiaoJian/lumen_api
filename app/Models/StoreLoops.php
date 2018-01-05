<?php

namespace App\Models;

use App\Api\Traits\Orm\DataSortTrait;
use Illuminate\Database\Eloquent\Model;

class StoreLoops extends Model
{
    use DataSortTrait;
    public $timestamps = false;
    protected $table = 'store_loops';
}

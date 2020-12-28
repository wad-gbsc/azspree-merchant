<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusModel extends Model
{
    protected $table = 'odst';
    protected $primaryKey = 'order_hash';
    public $timestamps = false;
}

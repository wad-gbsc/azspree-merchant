<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $table = 'inmr';
    protected $primaryKey = 'inmr_hash';
    public $timestamps = false;
}

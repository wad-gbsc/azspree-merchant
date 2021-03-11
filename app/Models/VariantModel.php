<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantModel extends Model
{
    protected $table = 'vrnt';
    protected $primaryKey = 'vrnt_hash';
    public $timestamps = false;
}

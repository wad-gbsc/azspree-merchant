<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumrModel extends Model
{
    protected $table = 'sumr';
    protected $primaryKey = 'sumr_hash';
    public $timestamps = false;
}

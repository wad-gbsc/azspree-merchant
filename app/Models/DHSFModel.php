<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DHSFModel extends Model
{
    protected $table = 'dhsf';
    protected $primaryKey = 'dh_hash';
    public $timestamps = false;
}

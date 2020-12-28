<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolnModel extends Model
{
    protected $table = 'soln';
    protected $primaryKey = 'soln_hash';
    public $timestamps = false;
}

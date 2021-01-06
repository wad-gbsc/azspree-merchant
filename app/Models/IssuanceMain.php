<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuanceMain extends Model
{
    protected $table = 'ismn';
    protected $primaryKey = 'issuance_hash';
    public $timestamps = false;
}

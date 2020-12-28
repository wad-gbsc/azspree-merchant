<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    
class DHTFModel extends Model
{
    protected $table = 'dhtf';
    protected $primaryKey = 'dhtf_hash';
    public $timestamps = false;
}

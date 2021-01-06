<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuanceDetails extends Model
{
    protected $table = 'isdt';
    protected $primaryKey = 'issuance_details_hash';
    public $timestamps = false;
}

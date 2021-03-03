<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesModel extends Model
{
    protected $table = 'imgs';
    protected $primaryKey = 'img_hash';
    public $timestamps = false;
}

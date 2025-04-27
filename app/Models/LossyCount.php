<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LossyCount extends Model
{
//    protected $guarded = false;

    protected $fillable = ['element', 'frequency', 'bucket'];
}
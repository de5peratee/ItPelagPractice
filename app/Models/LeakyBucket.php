<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeakyBucket extends Model
{

    protected $guarded = false;

    protected $casts = [
        'last_checked' => 'datetime',
    ];
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloomItem extends Model
{
    protected $fillable = ['item', 'hashes'];

    protected $casts = [
        'hashes' => 'array',
    ];
}
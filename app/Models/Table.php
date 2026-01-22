<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['table_number', 'is_occupied'];

    protected $casts = [
        'is_occupied' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchColors extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'hex_code',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DialColorList extends Model
{
    use HasFactory;
    protected $fillable = [
        'dial_color_name',
        'dial_color_code',
    ];
}

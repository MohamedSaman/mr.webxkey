<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassTypeList extends Model
{
    use HasFactory;
    protected $fillable = [
        'glass_type_name',
    ];
}

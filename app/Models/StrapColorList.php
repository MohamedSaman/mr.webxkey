<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrapColorList extends Model
{
    use HasFactory;
    protected $fillable = [
        'strap_color_name',
    ];
    protected $table = 'strap_color_lists';
}

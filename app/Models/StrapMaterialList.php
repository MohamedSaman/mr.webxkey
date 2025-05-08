<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrapMaterialList extends Model
{
    use HasFactory;
    protected $fillable = [
        'strap_material_name',
        'material_quality',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchMadeBy extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_name',
    ];
    protected $table = 'watch_made_bies';
}

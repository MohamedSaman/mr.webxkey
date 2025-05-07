<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WatchCategory extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description'];
    
    /**
     * Get the watches in this category
     */
    public function watches(): HasMany
    {
        return $this->hasMany(WatchDetail::class, 'category_id');
    }
}

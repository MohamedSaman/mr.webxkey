<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WatchBrand extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description'];
    
    /**
     * Get the watches for this brand
     */
    public function watches(): HasMany
    {
        return $this->hasMany(WatchDetail::class, 'brand_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WatchSupplier extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'contact', 'address', 'email', 'phone'];
    
    /**
     * Get the watches from this supplier
     */
    public function watches(): HasMany
    {
        return $this->hasMany(WatchDetail::class, 'supplier_id');
    }
}

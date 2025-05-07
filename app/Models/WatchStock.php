<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchStock extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'shop_stock', 'store_stock', 'damage_stock', 
        'total_stock', 'available_stock', 'watch_id'
    ];
    
    /**
     * Get the watch that owns this stock information
     */
    public function watch(): BelongsTo
    {
        return $this->belongsTo(WatchDetail::class, 'watch_id');
    }
    
    /**
     * Update total and available stock automatically
     */
    public function updateTotals()
    {
        $this->total_stock = $this->shop_stock + $this->store_stock + $this->damage_stock;
        $this->available_stock = $this->shop_stock + $this->store_stock;
        $this->save();
    }
}

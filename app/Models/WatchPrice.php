<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchPrice extends Model
{
    use HasFactory;
    
    protected $fillable = ['supplier_price', 'selling_price', 'discount_price', 'watch_id'];
    
    /**
     * Get the watch that owns this price information
     */
    public function watch(): BelongsTo
    {
        return $this->belongsTo(WatchDetail::class, 'watch_id');
    }
    
    /**
     * Calculate the profit margin percentage
     */
    public function getProfitMarginAttribute()
    {
        if ($this->supplier_price > 0) {
            $price = $this->discount_price ?? $this->selling_price;
            return (($price - $this->supplier_price) / $this->supplier_price) * 100;
        }
        return 0;
    }
}

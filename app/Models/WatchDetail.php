<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WatchDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'name', 'model', 'color', 'made_by', 'gender',
        'type', 'movement', 'dial_color', 'strap_color', 'strap_material',
        'case_diameter_mm', 'case_thickness_mm', 'glass_type', 
        'water_resistance', 'features', 'image', 'warranty',
        'description', 'barcode', 'status', 'location',
        'brand_id', 'category_id', 'supplier_id'
    ];
    
    /**
     * Get the brand of the watch
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(WatchBrand::class);
    }
    
    /**
     * Get the category of the watch
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(WatchCategory::class);
    }
    
    /**
     * Get the supplier of the watch
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(WatchSupplier::class);
    }
    
    /**
     * Get the price information for the watch
     */
    public function price(): HasOne
    {
        return $this->hasOne(WatchPrice::class, 'watch_id');
    }
    
    /**
     * Get the stock information for the watch
     */
    public function stock(): HasOne
    {
        return $this->hasOne(WatchStock::class, 'watch_id');
    }
}

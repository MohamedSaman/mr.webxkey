<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WatchDetail;

class StaffProduct extends Model
{
    // Specify the table if it does not follow Laravel conventions
    protected $table = 'staff_products';

    // Fillable fields for mass assignment
    protected $fillable = [
        'watch_id',     // foreign key to watch_details
        'staff_id',
        'quantity',
        'price',
        // add other fields here
    ];

    /**
     * Relationship: StaffProduct belongs to one WatchDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function watchDetail()
    {
        // 'watch_id' is the foreign key in staff_products table
        // pointing to the 'id' of the watch_details table
        return $this->belongsTo(WatchDetail::class, 'watch_id');
    }
    public function watch()
{
    return $this->belongsTo(WatchDetail::class, 'watch_id');
}
    public function staffSale()
    {
        return $this->belongsTo(StaffSale::class, 'staff_id');
    }

    /**
     * Get the total price for the product
     *
     * @return float
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'staff_sale_id',
        'watch_id',
        'staff_id',
        'quantity',
        'unit_price',
        'discount_per_unit',
        'total_discount',
        'total_value',
        'sold_quantity',
        'sold_value',
        'status',
    ];
    
    public function staffSale()
    {
        return $this->belongsTo(StaffSale::class);
    }
    
    public function watch()
    {
        return $this->belongsTo(WatchDetail::class, 'watch_id');
    }
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}

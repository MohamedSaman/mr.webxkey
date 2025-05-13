<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'watch_id',
        'watch_code',
        'watch_name',
        'quantity',
        'unit_price',
        'discount',
        'total',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function watch()
    {
        return $this->belongsTo(WatchDetail::class, 'watch_id');
    }
}

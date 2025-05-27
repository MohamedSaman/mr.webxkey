<?php

namespace App\Models;

use App\Models\WatchDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

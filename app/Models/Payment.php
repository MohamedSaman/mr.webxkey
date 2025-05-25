<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'amount',
        'payment_method',
        'payment_reference',
        'card_number',
        'bank_name',
        'is_completed',
        'payment_date',
        'due_date',
        'due_payment_method',
        'due_payment_attachment',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'due_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

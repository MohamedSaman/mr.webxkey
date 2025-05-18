<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_type',
        'subtotal',
        'discount_amount',
        'total_amount',
        'payment_type',
        'payment_status',
        'notes',
        'due_amount',
        'user_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    // Generate unique invoice numbers
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();
            
        $nextNumber = 1;
        
        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            $lastNumber = intval(end($parts));
            $nextNumber = $lastNumber + 1;
        }
        
        return $prefix . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

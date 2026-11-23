<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'total_amount',
        'payment_method',
        'cash_received',
        'change_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    // Customer who purchased
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Staff who recorded the sale
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Products included in the transaction
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}

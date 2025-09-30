<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnSale extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'customer_id',
        'payment_method_id',
        'date',
        'total_refund',
        'notes',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function returnSaleDetails()
    {
        return $this->hasMany(ReturnSaleDetail::class);
    }
}

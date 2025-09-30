<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnPurchase extends Model
{
    protected $fillable = [
        'purchase_id',
        'user_id',
        'supplier_id',
        'payment_method_id',
        'date',
        'total_return',
        'notes',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function returnPurchaseDetails()
    {
        return $this->hasMany(ReturnPurchaseDetail::class);
    }
}

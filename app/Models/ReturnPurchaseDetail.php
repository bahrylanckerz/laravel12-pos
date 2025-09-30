<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnPurchaseDetail extends Model
{
    protected $fillable = [
        'return_purchase_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'notes',
    ];

    public function returnPurchase()
    {
        return $this->belongsTo(ReturnPurchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

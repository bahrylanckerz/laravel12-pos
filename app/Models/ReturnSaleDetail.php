<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnSaleDetail extends Model
{
    protected $fillable = [
        'return_sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'notes',
    ];

    public function returnSale()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

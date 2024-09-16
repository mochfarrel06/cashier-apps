<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'cashier_product_id',
        'quantity',
        'price',
        'purchase_type',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function cashierProduct()
    {
        return $this->belongsTo(CashierProduct::class);
    }
}

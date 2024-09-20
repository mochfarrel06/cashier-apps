<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_product_id',
        'stock_date',
        'stock_in',
        'stock_out',
        'current_stock',
    ];

    // Relasi ke User

    // Relasi ke Product
    public function cashierProduct()
    {
        return $this->belongsTo(CashierProduct::class);
    }
}

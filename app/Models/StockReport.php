<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'flavor_id',
        'report_date',
        'stock',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Flavor
    public function flavor()
    {
        return $this->belongsTo(Flavor::class);
    }
}

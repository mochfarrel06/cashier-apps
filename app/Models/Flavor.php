<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flavor extends Model
{
    use HasFactory;
    protected $table = 'flavors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id',
        'flavor_name',
        'price_retail',
        'price_pack'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function cashierProduct()
    {
        return $this->hasMany(CashierProduct::class);
    }
}

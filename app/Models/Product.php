<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'description',
        'items_per_pack',
        'photo'
    ];

    public function flavor()
    {
        return $this->hasMany(Flavor::class);
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

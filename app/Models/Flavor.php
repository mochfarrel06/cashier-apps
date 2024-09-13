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
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

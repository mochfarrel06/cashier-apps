<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartProduct()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}

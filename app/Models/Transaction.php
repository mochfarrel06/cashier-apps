<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cart_id', 'user_id', 'transaction_number', 'total', 'payment_type',
    ];

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

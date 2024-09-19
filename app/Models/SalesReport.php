<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'report_date',
        'total_sales',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

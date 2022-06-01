<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'users_id',
        'address',
        'payment_method',
        'payment_status',
        'payment_code',
        'total_price',
        'shipping_price',
        'shipping_status',
        'shipping_code',
        'shipping_type',
        'shipping_service',
    ];

    // belongto artinya 1 user bisa punya banyak transaksi
    public function user() {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    // hasmany artinya 1 transaksi bisa punya banyak detail
    public function transactionDetail() {
        return $this->hasMany(TransactionDetails::class, 'transaction_id', 'id');
    }
}

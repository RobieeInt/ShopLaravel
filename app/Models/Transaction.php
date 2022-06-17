<?php

namespace App\Models;

use App\Models\User;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function items() {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}

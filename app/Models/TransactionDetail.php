<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'qty',
    ];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

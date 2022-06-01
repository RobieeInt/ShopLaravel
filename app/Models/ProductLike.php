<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLike extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'users_id',
        'products_id',
    ];

    public function user() {
        return $this->hasMany(User::class, 'users_id', 'id');
    }

    public function product() {
        return $this->hasMany(Product::class, 'products_id', 'id');
    }
}

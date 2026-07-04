<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'product_id', 'price', 'quantity'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}

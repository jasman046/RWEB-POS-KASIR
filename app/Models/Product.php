<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'image', 'description', 'stock'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'type', 'customer_name', 'total_price', 'status', 'payment_method'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function generateOrderNumber()
    {
        $number = '#' . str_pad((Order::max('id') + 1), 3, '0', STR_PAD_LEFT);
        return $number;
    }
}
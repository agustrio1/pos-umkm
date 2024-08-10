<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'discount', 'status', 'payment_method', 'total'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->orderItems as $orderItem) {
            if ($orderItem->product) {
                $total += $orderItem->quantity * $orderItem->price;
            }
        }
        return $total;
    }

    protected static function booted()
    {
        static::saving(function ($order) {
            // Menghitung total setelah discount
            $calculatedTotal = $order->calculateTotal();
            $order->total = $calculatedTotal - $order->discount;
        });
    }
}

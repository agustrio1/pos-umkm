<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price', 'total'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'integer',
        'total' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            $orderItem->total = $orderItem->quantity * $orderItem->price;

            // Kurangi stok produk
            $orderItem->product->stock -= $orderItem->quantity;
            $orderItem->product->save();
        });

        static::updating(function ($orderItem) {
            $orderItem->total = $orderItem->quantity * $orderItem->price;
        });
    }
}

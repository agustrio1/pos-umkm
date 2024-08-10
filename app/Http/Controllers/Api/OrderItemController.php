<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderItem = new OrderItem($request->all());
        $orderItem->order_id = $orderId;
        $orderItem->save();

        return response()->json($orderItem, 201);
    }

    public function update(Request $request, $orderId, $id)
    {
        $orderItem = OrderItem::where('order_id', $orderId)->find($id);
        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $orderItem->update($request->all());

        return response()->json($orderItem);
    }

    public function destroy($orderId, $id)
    {
        $orderItem = OrderItem::where('order_id', $orderId)->find($id);
        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $orderItem->delete();

        return response()->json(['message' => 'Order item deleted']);
    }
}

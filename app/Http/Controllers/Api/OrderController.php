<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('orderItems')->get();
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return $order;
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'total' => 'required|numeric',
        ]);

        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->update($request->all());

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted']);
    }
}

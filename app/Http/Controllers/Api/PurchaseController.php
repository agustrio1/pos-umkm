<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return Purchase::with('product', 'supplier')->get();
    }

    public function show($id)
    {
        $purchase = Purchase::with('product', 'supplier')->find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }
        return $purchase;
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $purchase = Purchase::create($request->all());

        return response()->json($purchase, 201);
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        $purchase->update($request->all());

        return response()->json($purchase);
    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted']);
    }
}

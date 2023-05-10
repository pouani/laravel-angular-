<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {

        try {
            //get products orders


            $orders = auth()->user()->orders;
            //return orders with user and products
            return response()->json([
                'orders' => $orders,
                'user' => auth()->user()
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ], 500);
        }

    }

    public function show($id){
        $order = auth()->user()->orders()->where('id', $id)->first();
        return response()->json(['order' => $order], 200);
    }

    public function store(Request $request)
{
    $this->validate($request, [
        'product_id' => 'required|array',
        'quantity' => 'required|integer|min:1',
    ]);

    try {
        // Random order number
        $random = rand(1000, 9999);
        $order_number = 'ORD-' . $random;

        $productIds = [];

        foreach ($request->product_id as $productId) {
            $order = Order::create([
                'order_number' => uniqid($order_number),
                'user_id' => auth()->user()->id,
                'product_id' => $productId,
                'quantity' => $request->quantity,
            ]);

            $product = Product::find($productId);
            if ($product) {
                $product->delete();
                $productIds[] = $productId;
            }
        }

        return response()->json([
            'orders' => $order,
            'product_ids' => $productIds,
            'product' => $product,
            'message' => 'Commande effectuée avec succès',
        ], 200);
    } catch (Exception $ex) {
        return response()->json([
            'success' => false,
            'message' => $ex->getMessage(),
        ], 500);
    }
}

    public function update(Request $request, $id){
        $order = auth()->user()->orders()->where('id', $id)->first();

        if($order){
            $order->quantity = $request->quantity;
            $order->save();

            return response()->json(['order' => $order], 200);
        }else{
            return response()->json(['message' => 'Commande introuvable'], 404);
        }
    }

    public function destroy($id){
        $order = auth()->user()->orders()->where('id', $id)->first();

        if($order){
            $order->delete();
            return response()->json(['message' => 'Commande supprimée'], 200);
        }else{
            return response()->json(['message' => 'Commande introuvable'], 404);
        }
    }
}

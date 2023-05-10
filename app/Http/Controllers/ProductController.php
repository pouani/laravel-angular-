<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json(
                $products
            ,200);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ],500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:300',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "success" => false,
                    "message" => $validator->errors()
                ]);
            }

            $product = Product::create([
                'name' => $request->name, 
                'price' => $request->price, 
                'description' => $request->description,
            ]);
            $product = $product->fresh();

            return response()->json([
                'success' =>true,
                'message' => 'Nouveau produit ajouté!!!'
            ],200);

        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ],500);
        }
    }


    public function destroy($id)
    {
        try {
            Product::where('id', $id)->delete();
            return response()->json([
                'success' =>true,
                'message' => 'Produit Supprimé!!!'
            ],200);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ],500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    //product listin 
    public function index(Request $request){
         $product = Product::all();
         return response()->json([
            'status' => true , 
            'message' =>"product listed succesfully",
            'data' =>$product
         ],200) ;
    }

      //add product 
      public function create(Request $request) {
         $validateProduct = Validator::make($request->all(), [
             'product_name' => 'required|unique:products,product_name',
             'quantity' => 'required|numeric',
             'price' => 'required|numeric|between:0,999999.99'
         ]);
     
         if ($validateProduct->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => "Validation error",
                 'data' => $validateProduct->errors()
             ], 422);
         }
     
         $inputData = [
             'product_name' => $request->product_name,
             'quantity' => $request->quantity,
             'price' => $request->price,
             'description' => $request->description ?? ''
         ];
     
         $product = Product::create($inputData);
     
         return response()->json([
             'status' => true,
             'message' => "Product added successfully",
             'data' => $product
         ], 200);
     }
      //update product 
      public function update(Request $request) {
        $validateProduct = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|between:0,999999.99'
        ]);
    
        if ($validateProduct->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'data' => $validateProduct->errors()
            ], 422);
        }
    
        $product = Product::find($request->product_id);
        
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => "Product not found",
            ], 404);
        }
    
        $product->product_name = $request->product_name;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->description = $request->description ?? '';
        $product->save();
    
        return response()->json([
            'status' => true,
            'message' => "Product updated successfully",
            'data' => $product
        ], 200);
    }
    public function delete(Request $request) {
        // Validation des données de la requête
        $validateProduct = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);
    
        // Vérification des erreurs de validation
        if ($validateProduct->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'data' => $validateProduct->errors()
            ], 422);
        }
    
        // Recherche du produit à supprimer
        $product = Product::find($request->product_id);
    
        // Vérification si le produit existe
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => "Product not found",
            ], 404);
        }
    
        // Suppression du produit
        $product->delete();
    
        // Réponse JSON indiquant que le produit a été supprimé avec succès
        return response()->json([
            'status' => true,
            'message' => "Product deleted successfully",
            'data' => $product
        ], 200);
    }
      
}

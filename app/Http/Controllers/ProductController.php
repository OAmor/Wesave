<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    public function update(Request $request, $product_id){
        $request->validate([

        ]);
        $product = auth()->user()->products()->where('id', $product_id)->first();
        if(!$product){
            return [
                'status' => 400,
                'message' => 'The product you\'re looking for does not exist',
            ];
        }
        $data = $request->all();
        $product->update($data);
        return [
            'status' => 200,
            'message' => 'You now have '.$product->quantity.' of '.$product->name,
        ];
    }
    public function all(){
        $products = Auth::user()->products;
        return $products;
    }
    public function delete(Request $request, $product_id){
        $product = auth()->user()->products()->where('id', $product_id)->first();
        if(!$product){
            return [
                'status' => 400,
                'message' => 'The product you\'re looking for does not exist',
            ];
        }
        $product->delete();
        return [
            'status' => 200,
            'message' => 'The product has been deleted successfully',
        ];
    }
}

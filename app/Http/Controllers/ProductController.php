<?php

namespace App\Http\Controllers;

use App\Product;
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

    public function getNeeded(){
        try{
            $products = Product::where('qte',0)->get();
            return response()->json([
                'success' => true,
                'products' => $products
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function validateRecipe(Request $request){
        $request->validate([
            'uri' => 'required',
        ]);
        $recipe_uri = $request->uri;
        $req = "https://api.edamam.com/search?r=$recipe_uri&app_id=b32d3c32&app_key=8332f2c81bbe470211eb2d6886e90cb3";
        $response = json_decode($client->post($req)->getBody()->getContent(), true);
        $products = auth()->user()->products;
        $products = $products->filter(function($item){
            $ingredients = collect($response['ingredients']);
            $ingredients = $ingredients->pluck('text');
            return array_in($product->name, $ingredients);
        });
        $products->each(function($item){
            //The text returned by the api is in the format of : "3 teaspoons of tomato puree' for example... we can't get the qte need for the recipe this easily, i removed only 1 from qte
            $item->qte -= 1;
            $item->save();
        });
        return [
            'status' => 200,
            'message' => 'The ingredient has been validated successfully !',
        ];
    }

    public function getProductsByCat($cat){
        try{
            $products = Auth::user()->products;
            $res = [];
            foreach ($products as $p){
                if( $p->category == $cat )array_push($res,$p);
            }
            return response()->json([
                'success' => true,
                'products' => $res
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

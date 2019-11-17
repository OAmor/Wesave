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
        return response()->json([
            'success' => true,
            'products' => $products
        ], 200);
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

    public function getMissedProducts(){
        $cats = Product::select('category')->distinct()->get();
        $res = [];

        foreach ($cats as $cat){
            array_push($res,[
                'id' => $cat['category'],
                'products' => $this->getProductsCat($cat['category'])
            ]);
        }

        return ['result' => $res];

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

    private function getProductsCat($cat){
        $products = Auth::user()->products;
        $res = [];
        foreach ($products as $p){
            if( $p->category == $cat ){
                $p['weight'] = rand(1,500);
                array_push($res,$p);
            }
        }
        return $res;
    }
}

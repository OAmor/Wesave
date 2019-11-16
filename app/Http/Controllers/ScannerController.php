<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Product;
use App\DummyProduct;
use Carbon\Carbon;
use Log;
use GuzzleHttp\Client;


class ScannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'qrcode' => 'required'
            ]);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'validation_errors' => $validator->errors()
                ], 401);
            }
            $qrcode = $request->qrcode;
            $codes =explode("|",$qrcode);

            $products = [];
            foreach ($codes as $code){
                $barcode = substr($code,0,12);
                $p = DummyProduct::where('barcode',$barcode)->first();
                if(!$p) continue;
                array_push($products,[
                    'barcode' => $barcode,
                    'name' => $p->name,
                    'qte' => substr($code,12,strlen($code)),
                    'created_at' => Carbon::now(),
                    'category' => $p->category,
                    'image' => $p->image,
                    'user_id' => Auth::user()->id
                ]);
            }
            Product::insert($products);
            return response()->json([
                'success' => true,
                'message' => 'productsAddedSuccessfully'
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getRecipes(Request $request){
        $user = Auth::user();
        $products = $user->products;
        $arr=[];
        foreach ($products as $p){
            array_push($arr,$p->name);
        }
        $combs = $this->get_array_combination($arr);
        $client = new Client();
        $res = [];

        foreach ($combs as $comb ){
            $search = implode(",", $comb);
            $req = "https://api.edamam.com/search?q=$search&app_id=b32d3c32&app_key=8332f2c81bbe470211eb2d6886e90cb3&from=0&to=3&calories=591-722&health=alcohol-free";
            $response = $client->post($req);
            array_push($res,json_decode($response->getBody()->getContents(),true));
        }
        return $this->treatData($res);
    }
    private function treatData($data){
        $res = [];
        foreach ($data as $d){
            foreach ($d['hits'] as $recipe){
                array_push($res,$recipe['recipe']);
            }
        }
        return $res;
    }

    public function test(){
        return "dd";
    }
    function get_array_combination($arr) {
        $results = array(array( ));

        foreach ($arr as $values)
            foreach ($results as $combination)
                array_push($results, array_merge($combination, array($values))); // Get new values and merge to your previous combination. And push it to your results array
        return $results;
    }
}

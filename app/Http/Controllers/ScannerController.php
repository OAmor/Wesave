<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Product;
use Carbon\Carbon;

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
                array_push($products,[
                    'barcode' => $code,
                    'name' => 'testProduct',
                    'created_at' => Carbon::now(),
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
                'message' => 'internalServerError'
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
}

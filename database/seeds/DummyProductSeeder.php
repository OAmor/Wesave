<?php

use Illuminate\Database\Seeder;
use App\DummyProduct;

class DummyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          ["name" => "banana", "barcode" => "123456789123","image"=>'images/banana.jpg',"category"=>"frutes"],
          ["name" => "potato", "barcode" => "123456789124","image"=>'images/potato.jpg',"category"=>"frutes"],
          ["name" => "tomato", "barcode" => "123456789125","image"=>'images/tomato.jpg',"category"=>"frutes"],
          ["name" => "apples", "barcode" => "123456789126","image"=>'images/apple.jpg',"category"=>"frutes"],
          ["name" => "carrot", "barcode" => "123456789127","image"=>'images/carrot.jpg',"category"=>"frutes"],
          ["name" => "orange", "barcode" => "123456789128","image"=>'images/orange.jpg',"category"=>"frutes"],

          ["name" => "chicken", "barcode" => "123456789129","image"=>'images/chicken.png',"category"=>"meats"],
          ["name" => "beef", "barcode" => "123456789110","image"=>'images/beef.jpg',"category"=>"meats"],
          ["name" => "eggs", "barcode" => "123456789111","image"=>'images/eggs.png',"category"=>"meats"]
        ];

        DummyProduct::insert($data);
    }
}

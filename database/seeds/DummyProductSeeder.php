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
          ["name" => "chicken", "barcode" => "123456789123"],
          ["name" => "potato", "barcode" => "123456789124"],
          ["name" => "tomato", "barcode" => "123456789125"],
          ["name" => "onion", "barcode" => "123456789126"],
          ["name" => "coke", "barcode" => "123456789127"],
          ["name" => "alcohol", "barcode" => "123456789128"],
          ["name" => "oil", "barcode" => "123456789129"],
          ["name" => "beef", "barcode" => "123456789110"],
          ["name" => "eggs", "barcode" => "123456789111"]
        ];
        DummyProduct::insert($data);
    }
}

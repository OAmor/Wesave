<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'qte','weight','user_id','name','barcode'
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $hidden = [
        'user_id'
    ];

    protected $fillable = [
        'qte','weight','user_id','name','barcode','category','image'
    ];

    public function owner(){
        return $this->belongsTo(User::class);
    }
}

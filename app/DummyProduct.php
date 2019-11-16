<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DummyProduct extends Model
{
    protected $fillable = [
        'code','name'
    ];
}

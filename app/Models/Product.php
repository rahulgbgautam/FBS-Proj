<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     public function ingredient(){
    	return $this->hasMany('App\Models\ProductIngredient','ingredient_id','id');
    }
    // protected $table = 'products';
}

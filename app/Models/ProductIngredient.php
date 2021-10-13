<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;


class ProductIngredient extends Model
{
    use HasFactory;
    protected $table = "product_ingredient";

    public function ingredient(){
    	return $this->hasMany('App\Models\Ingredient','id','ingredient_id');
    }
}

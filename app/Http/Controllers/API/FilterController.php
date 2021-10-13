<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProbsCategory;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Productvariant;
use App\Models\ProductIngredient;
use App\Models\Variant;
use App\Models\Ingredient;
use App\Models\Favourite;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
  public function getFilter(Request $request){
  	$data = array();
    $category = ProbsCategory::where('is_deleted','0')
    					->where('status','Active')
                       ->orderBy('category_name','ASC')
                       ->where('parent_id','=','')
                       ->get(); 
     $promotion = Promotion::where('is_deleted','0')
    					->where('status','Active')
                       ->get();  
	 $minMax = [0,12];                  
					 $data['category'] = $category;
					 $data['minMax'] = $minMax;
					 $data['promotion'] = $promotion;
					  $base_path = url('/uploads/');  
				       return response()->json([
				                'status' => true,
				                'path' =>  $base_path,
				                'data' => $data
				               ]);
    }
    public function applyFilter(Request $request){
	$data = array();
	$category = array();
	$promotion = array();
	$product = array();
	 if($request->category_id){
		$category = ProbsCategory::where('is_deleted','0')
				    ->where('status','Active')
				    ->orderBy('category_name','ASC')
				    ->where('parent_id','=','')
				    ->where('id','=',$request->category_id)
				    ->first();
				 }
	 	// dd($category);
	 if(($request->min!='') && ($request->max!='')){
		$product = Product::where('is_deleted','0')
				    ->where('status','Active')
				    ->whereBetween('base_price', [$request->min, $request->max])
				    ->get();
				 }
	if($request->promotion_id){
		$promotion = Promotion::where('is_deleted','0')
				    ->where('status','Active')
				    ->where('id','=',$request->promotion_id)
				         ->first();
				 }
				 $minMax = [0,12];                  
				 $data['category'] = $category;
				 $data['product'] = $product;
				 $data['promotion'] = $promotion;
				 $base_path = url('/uploads/');  

				 return response()->json([
				       'status' => true,
				       'path' =>  $base_path,
				       'data' => $data
				 ]);
				}
	public function seeSimilar(Request $request){
		$base_path = url('/uploads/'); 
		if($request->product_id){
			$ing = ProductIngredient::where('product_id',$request->product_id)->pluck('ingredient_id')->toArray();
		}
		$data['list'] = Product::select('products.*')
			->join('product_ingredient','product_ingredient.product_id','=','products.id')
			->whereIn('ingredient_id',$ing)
			->where('products.id','!=',$request->product_id)
			->groupBy('products.id')
			->get();
				return response()->json([
				'status' => true,
				'path' =>  $base_path,
				'data' => $data
				 ]);
	}

}

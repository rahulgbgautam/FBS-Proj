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
use App\Models\ProductQuantity;
use App\Models\RecentlyViewed;
use Illuminate\Support\Facades\DB;
use Auth;


class HomeController extends Controller
{
    
    public function getData(Request $request){

        $user_id = $request->user_id;   
        $device_id = $request->device_id;   
        $data = array();
        $page = paginationValue();
        $category = ProbsCategory::where('is_deleted','0')
                        ->where('status','Active')
                        ->orderBy('category_name','ASC')
                        ->where('parent_id','=','')
                        ->get();             

        $promotion = Promotion::where('is_deleted','0')
                        ->where('status','Active')
                        ->orderBy('discount','ASC')
                        ->get(); 

        $recommended = Product::where('is_deleted','0')
                                ->where('status','Active')
                                ->leftjoin('favourites', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('products.id', '=', 'favourites.product_id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('favourites.user_id', '=', $user_id)
                                                           ->orWhere('favourites.device_id', '=', $device_id);
                                                     });
                                           })
                                        ->leftJoin('product_quantity', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('product_quantity.product_id', '=', 'products.id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('product_quantity.user_id', '=', $user_id)
                                                           ->orWhere('product_quantity.device_id', '=', $device_id);
                                                     });
                                           });
                                                                        
        $recommendedProd = $recommended->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'),'product_quantity.quantity')
                                     ->groupBy('products.id')
                                     ->get();  


        $allProductId = RecentlyViewed::where('user_id',$user_id)
                                        ->pluck('product_id')
                                        ->toArray();

        $allProductInfo  =  Product::whereIn('products.id',$allProductId)
                                            ->leftjoin('favourites', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('products.id', '=', 'favourites.product_id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('favourites.user_id', '=', $user_id)
                                                           ->orWhere('favourites.device_id', '=', $device_id);
                                                     });
                                           })
                                        ->leftJoin('product_quantity', function($join)  use ($device_id,$user_id)
                                           {
                                              $join->on('product_quantity.product_id', '=', 'products.id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('product_quantity.user_id', '=', $user_id)
                                                           ->orWhere('product_quantity.device_id', '=', $device_id);
                                                     });
                                           });                                        

        $recentlyViewed = $allProductInfo->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'),'product_quantity.quantity')
                                     ->groupBy('products.id')
                                     ->get();   

                                                
        $base_path = url('/uploads/');                               
        $data['category'] = $category;
        $data['promotion'] = $promotion;
        $data['recommended'] = $recommendedProd;
        $data['recentlyViewed'] = $recentlyViewed;
        return response()->json([
                    'status' => true,
                    'path' =>  $base_path,
                    'data' => $data
                ]);             
    }    

    public function probsSubCateogry(Request $request){

        $id = $request->probs_category_id;
        $data = array();

        $probsCateogry = ProbsCategory::select('*')
                ->where('is_deleted','0')
                ->where('status','Active')
                ->where('parent_id',$id);

        $probsSubCateogry = ProbsCategory::select('*')
                ->where('is_deleted','0')
                ->where('status','Active')
                ->where('id',$id)
                ->union($probsCateogry)
                ->get();

        $probsSubCateogry[0]->category_name = 'All';
        $base_path = url('/uploads/');   
        $data['probsSubCateogry'] = $probsCateogry;
        return response()->json([
                    'status' => true,
                    'path' => $base_path,
                    'data' => $probsSubCateogry
                ]);     

    }   

    public function getParentInfo($id){
        $data = getParentsInfo($id);
        return response()->json([
                        'status' => true,
                        'data' => $data
                    ]); 
    }

    public function productInfo(Request $request){
        $id = $request->product_id;
        $data = array();
        $productInfo = Product::where('is_deleted','0')
                        ->where('status','Active')
                        ->leftJoin('favourites', 'products.id', '=', 'favourites.product_id')
                        ->where('products.id',$id);

        $productslist = $productInfo->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'))
                ->first();                
            

        $allIngredientId = ProductIngredient::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->where('product_id',$id)
                                        ->pluck('ingredient_id')
                                        ->toArray();

        $allIngredientInfo  =  Ingredient::whereIn('id',$allIngredientId)
                                    ->get();


        $allVariantId = Productvariant::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->where('product_id',$id)
                                        ->pluck('variant_id')
                                        ->toArray();

        $allVariantInfo  =  Variant::whereIn('id',$allVariantId)
                                    ->get();                                                                             

        $prodData['productInfo'] = $productslist;
        $prodData['allIngredientInfo'] = $allIngredientInfo;
        $prodData['allVariantInfo'] = $allVariantInfo;

        if ($request->user_id){ 
                $data = new RecentlyViewed;
                $data->user_id = $request->user_id;
                $data->product_id = $request->product_id;
                $data->save();
        }else{
                $data = new RecentlyViewed;
                $data->device_id = $request->device_id;
                $data->product_id = $request->product_id;
                $data->save();
        }  

        $base_path = url('/uploads/');
        return response()->json([
                    'status' => true,
                    'path' =>  $base_path,
                    'message' =>"Product added successfully.",
                    'data' => $prodData
                ]);  
    }

    public function allProductInfo(Request $request){

        $category_id = $request->probs_sub_category_id;
        $all_data = $request->all_data;
        $device_id = $request->device_id;
        $user_id = $request->user_id;
        $page = paginationValue();
        $base_path = url('/uploads/');
        $total = 0;
            if ($request->user_id){ 
                $productData = ProductQuantity::where('user_id',$request->user_id)
                                                // ->where('user_id',$request->probs_sub_category_id)
                                                ->get();
                foreach ($productData as $value) {
                    $total = $total+$value->quantity*$value->base_price;
                }

        }else{
            $productData = ProductQuantity::where('device_id',$request->device_id)->get();
            foreach ($productData as $value){
                    $total = $total+$value->quantity*$value->base_price;
                }

        }     

        if($all_data == "yes"){

            $probsSubCateogry = ProbsCategory::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->where('parent_id',$category_id)
                                        ->pluck('id')
                                        ->toArray();

            $categoryProductId = CategoryProduct::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->whereIn('category_id',$probsSubCateogry)
                                        ->pluck('product_id')
                                        ->toArray();                            
            // dd($categoryProductId);
            $productslist =   Product::whereIn('products.id',$categoryProductId)
                                        ->leftjoin('favourites', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('products.id', '=', 'favourites.product_id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('favourites.user_id', '=', $user_id)
                                                           ->orWhere('favourites.device_id', '=', $device_id);
                                                     });
                                           })
                                        ->leftjoin('product_quantity', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('product_quantity.product_id', '=', 'products.id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('product_quantity.user_id', '=', $user_id)
                                                           ->orWhere('product_quantity.device_id', '=', $device_id);
                                                     });
                                           });
                                                                                
            $productslist = $productslist->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'),'product_quantity.quantity')
                                     ->groupBy('products.id')
                                     ->paginate($page);                                                   
                                                     

        }else{

                $allProductId = CategoryProduct::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->where('category_id',$category_id)
                                        ->pluck('product_id')
                                        ->toArray();

                $allProductInfo  =  Product::whereIn('products.id',$allProductId)
                                            ->leftjoin('favourites', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('products.id', '=', 'favourites.product_id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('favourites.user_id', '=', $user_id)
                                                           ->orWhere('favourites.device_id', '=', $device_id);
                                                     });
                                           })
                                        ->leftjoin('product_quantity', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('product_quantity.product_id', '=', 'products.id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('product_quantity.user_id', '=', $user_id)
                                                           ->orWhere('product_quantity.device_id', '=', $device_id);
                                                     });
                                           });                                        

            $productslist = $allProductInfo->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'),'product_quantity.quantity')
                                     ->groupBy('products.id')
                                     ->paginate($page);   
        }

        return response()->json([
                            'status' => true,
                            'path' =>  $base_path,
                            'total' => $total,
                            'data' => $productslist,
                        ]);
        
                                   
    }

    public function allVariantInfo($id){
        $allVariantId = Productvariant::where('is_deleted','0')
                                        ->where('status','Active')
                                        ->where('product_id',$id)
                                        ->pluck('variant_id')
                                        ->toArray();

        $allVariantInfo  =  Variant::whereIn('id',$allVariantId)
                                    ->get();   

        $base_path = url('/uploads/');                                       
        return response()->json([
                    'status' => true,
                    'path' =>  $base_path,
                    'data' => $allVariantInfo
                ]);  
                                   
    }

    public function favourite(Request $request){
         
        $product_id = $request->product_id; 
        if($request->status=="yes"){
            if($request->user_id){
                $user_id = $request->user_id;            
                $favourite = new Favourite;
                $favourite->user_id = $user_id;
                $favourite->product_id = $product_id;
                $favourite->save();
                return response()->json([
                    'status' => true,
                    'message' =>"Item marked as favourite." 
                ]);  
            }else{
                    $device_id = $request->device_id;            
                    $favourite = new Favourite;
                    $favourite->device_id = $device_id;
                    $favourite->product_id = $product_id;
                    $favourite->save();
                    return response()->json([
                        'status' => true,
                        'message' =>"Item marked as unfavourite." 
                    ]); 
            }           
            
        }else{

                if($request->user_id){
                    $user_id = $request->user_id; 
                    $data = Favourite::where('user_id',$user_id)
                                ->where('product_id',$product_id)
                                ->first();
                }else{
                        $device_id = $request->device_id;
                        $data = Favourite::where('device_id',$device_id)
                                ->where('product_id',$product_id)
                                ->first();
                }  

                if($data){
                    $data->delete();
                    return response()->json([
                            'status' => true,
                            'message' =>"Favourite deleted successfully."
                        ]);
                }else{
                     return response()->json([
                            'status' => false,
                            'message' =>"product is not in table."
                        ]);
                }
                

        } 
        
                                   
    }


    public function recentlyViewed(Request $request){
        if ($request->user_id){ 
                $data = new RecentlyViewed;
                $data->user_id = $request->user_id;
                $data->product_id = $request->product_id;
                $data->save();
                return response()->json([
                 'status' => true,
                 'message' =>"Product added successfully."
             ]);
        }
        else{
                $data = new RecentlyViewed;
                $data->device_id = $request->device_id;
                $data->product_id = $request->product_id;
                $data->save();
                return response()->json([
                 'status' => true,
                 'message' =>"Product added successfully."
             ]);
        }  
                                   
    }


    public function customize(){

        $data = array();

        $ingredient = Ingredient::where('is_deleted','0')
                                ->where('status','Active')
                                ->latest('id')->get();

        $toppingsData = Variant::where('is_deleted','0')
                                ->where('status','Active')
                                ->where('type','=','toppings')
                                ->latest('id')->get(); 

        $dressingData = Variant::where('is_deleted','0')
                                ->where('status','Active')
                                ->where('type','=','dressing')
                                ->latest('id')->get(); 

        $data['ingredient'] = $ingredient;
        $data['toppings'] = $toppingsData;
        $data['dressing'] = $dressingData;

        $base_path = url('/uploads/');
        return response()->json([
                'status' =>true,
                'path' =>  $base_path,
                'data' =>$data
            ]);                                               
        
    }

    public function search(Request $request){
        if(($request->category_id) || (($request->min!='') && ($request->max!='')) || ($request->promotion_id)) {
            $category = $request->category_id;
            $promotion = $request->promotion_id;
            $min = $request->min;
            $max = $request->max;
            $products = DB::table('products')->select('products.*')->where('products.is_deleted','0')
                        ->join('category_products', 'products.id', '=', 'category_products.product_id')
                        ->leftJoin('product_promotion', 'products.id', '=', 'product_promotion.product_id');
            if($request->category_id){
                $subCategories = ProbsCategory::select('id')
                           ->where('is_deleted','0')
                           ->where('status','Active')
                           ->where('parent_id',$request->category_id)
                           ->get();
                $sub_cat = []; 
                foreach ($subCategories as $key => $value) {
                    array_push($sub_cat,$value->id);
                }
                $products->where(function($query) use ($sub_cat){
                        $query->whereIn('category_products.category_id', $sub_cat);
                });
            }
            if($request->promotion_id){
                $products->where(function($query1) use ($promotion){
                        $query1->where('product_promotion.promotion_id', '=', $promotion);
                });
            }
            if(($request->min!='') && ($request->max!='')){
                $products->where(function($query1) use ($min,$max){
                        $query1->whereBetween('products.base_price', [$min, $max]);
                });
            }
            $productslist = $products->paginate(10);
            $base_path = url('/uploads/');  
            return response()->json([
               'status' => true,
               'path' =>  $base_path,
               'data' => $productslist
            ]);
        }else{
            $search_data = $request->search_data;
            $device_id = $request->device_id;
            $user_id = $request->user_id;
            $data = array();
            $page = paginationValue();
            $products = DB::table('products')
                        ->join('category_products', 'products.id', '=', 'category_products.product_id')
                        ->join('ds_probs_category', 'ds_probs_category.id', '=', 'category_products.category_id')
                        ->join('product_ingredient', 'products.id', '=', 'product_ingredient.product_id')
                        ->join('ingredients', 'ingredients.id', '=', 'product_ingredient.ingredient_id')
                        ->leftjoin('favourites', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('products.id', '=', 'favourites.product_id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('favourites.user_id', '=', $user_id)
                                                           ->orWhere('favourites.device_id', '=', $device_id);
                                                     });
                                           })
                        // ->leftjoin('product_quantity', function($join)  use ($device_id,$user_id)
                        //    {
                        //        $join->on('product_quantity.product_id', '=', 'products.id');
                        //        $join->where('product_quantity.device_id', '=', $device_id);
                        //        $join->orWhere('product_quantity.user_id', '=', $user_id);
                        //    })
                        ->leftJoin('product_quantity', function($join)  use ($device_id,$user_id)
                                           {
                                               $join->on('product_quantity.product_id', '=', 'products.id')
                                                    ->where(function($q) use ($device_id,$user_id) {
                                                         $q->where('product_quantity.user_id', '=', $user_id)
                                                           ->orWhere('product_quantity.device_id', '=', $device_id);
                                                     });
                                           })
                        ->where('products.is_deleted','0')
                        ->where('products.status','Active');
            if($search_data){
                $products->where(function($query) use ($search_data){
                            $query->where('products.name', 'LIKE', '%'.$search_data.'%');
                            $query->orWhere('ds_probs_category.category_name', 'LIKE', '%'.$search_data.'%');
                            $query->orWhere('ingredients.name', 'LIKE', '%'.$search_data.'%');
                            });
                }

             $productslist = $products->select('products.*',DB::raw('IF(favourites.product_id>0, "Yes", "No") as is_favourites'),'product_quantity.quantity')
                                     ->groupBy('products.id')
                                     ->paginate(10);


            $base_path = url('/uploads/');
            return response()->json([
                        'status' => true,
                        'path' =>  $base_path,
                        'data' => $productslist
                    ]);
            
        }
    }

    public function productQuantity(Request $request){
        if ($request->user_id){
                $productData = ProductQuantity::where('user_id',$request->user_id)
                                                ->where('product_id',$request->product_id)
                                                ->first();
                if($productData){
                    $productData->quantity = $request->quantity;
                    $productData->update();
                    return response()->json([
                         'status' => true,
                         'message' =>"Product quantity updated successfully."
                    ]);    
                }else{
                    $data = new ProductQuantity;
                    $data->user_id = $request->user_id;
                    $data->product_id = $request->product_id;
                    $data->quantity = $request->quantity;
                    $data->base_price = $request->base_price;
                    if($request->category_id){
                        $data->category_id = $request->category_id;
                    }
                    $data->save();
                    return response()->json([
                         'status' => true,
                         'message' =>"Product added successfully."
                    ]);    
                }                                  
                
        }else{   
               $productData = ProductQuantity::where('device_id',$request->device_id)
                                                ->where('product_id',$request->product_id)
                                                ->first();
                if($productData){
                    $productData->quantity = $request->quantity;
                    $productData->update();
                    return response()->json([
                         'status' => true,
                         'message' =>"Product quantity updated successfully."
                    ]);    
                }else{
                        $data = new ProductQuantity;
                        $data->device_id = $request->device_id;
                        $data->product_id = $request->product_id;
                        $data->quantity = $request->quantity;
                        $data->base_price = $request->base_price;
                        if($request->category_id){
                            $data->category_id = $request->category_id;
                        }
                        $data->save();
                        return response()->json([
                         'status' => true,
                         'message' =>"Product added successfully."
                     ]);
                }

            }
    }        

     public function getProductQuantity(Request $request){
        $total = 0;
            if ($request->user_id){ 
                $productData = ProductQuantity::where('user_id',$request->user_id)->get();
                foreach ($productData as $value) {
                    $total = $total+$value->quantity*$value->base_price;
                }
                return response()->json([
                     'status' => true,
                     'total' => $total,
                     'data' =>$productData
                ]);
        }else{
            $productData = ProductQuantity::where('device_id',$request->device_id)->get();
            foreach ($productData as $value){
                    $total = $total+$value->quantity*$value->base_price;
                }
                return response()->json([
                     'status' => true,
                     'total' => $total,
                     'data' =>$productData
                ]);
        }        

    }

}


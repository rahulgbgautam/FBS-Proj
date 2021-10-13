<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\ProductIngredient;
use App\Models\Ingredient;
use App\Models\Variant;
use App\Models\Promotion;
use App\Models\Productvariant;
use App\Models\Productpromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = trim($request->search);
        $page = paginationValue();
        $query = Product::where('is_deleted','0')
                                ->latest('id');

        if($search){
            $query->where('name','LIKE','%'.$search.'%');
        }
        $ProductData = $query->paginate($page);
        return view('admin.product.productList',compact('ProductData','search'));
        // return view('admin.domains.domainsList',compact('content','search','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->orderBy('category_name','ASC')
                        ->where('status','Active')
                        ->where('parent_id','!=','')
                        ->get();

        $Ingredient = Ingredient::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('name','ASC')
                                ->get(); 

        $Variant = Variant::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('name','ASC')
                                ->get();

        $Promotion = Promotion::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('title','ASC')
                                ->get(); 

        return view('admin.product.productAdd',compact('categoryList','Ingredient','Variant','Promotion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     

          $validatedData = $request->validate([
                'name'=>'required|max:100',
                'description'=>'required|max:9999',
                'base_price'=>'required|numeric|min:1',
                'discounted_price'=>'required|numeric|min:1',
                'weight'=>'required|numeric|min:1',
                'image'=>'required|image|mimes:jpg,png,jpeg,svg',
                'category_id'=>'required',
                'ingredient_id'=>'required',
                'variant_id'=>'required'
            ]);

          if($request->discounted_price>$request->base_price){
                     return back()->with([
                                        'discounted_price_greater'=>'Discounted price should be less from base price'
                                        ]);  
                }

            $ingredient_id = $request->ingredient_id;
            $variant_id = $request->variant_id;
            $promotion_id = $request->promotion_id;
            $user_id = Auth::user()->id;
             if($request->file('image')){
                $img_path = uploadImage($request->file('image'));
                $data = new Product;
                $data->name = $request->name;
                $data->base_price = $request->base_price;
                $data->discounted_price = $request->discounted_price;
                $data->weight = $request->weight;
                $data->description = $request->description;
                $data->image = $img_path;
                $data->created_by = $user_id;
                $data->updated_by = $user_id;
                $data->save();
                $CategoryProduct = new CategoryProduct;
                $CategoryProduct->category_id = $request->category_id;
                $CategoryProduct->product_id = $data->id;
                $CategoryProduct->created_by = $user_id;
                $CategoryProduct->updated_by = $user_id;
                $CategoryProduct->save();
                foreach ($ingredient_id as $key => $value) {
                    $ProductIngredient = new ProductIngredient;
                    $ProductIngredient->product_id = $data->id;
                    $ProductIngredient->ingredient_id = $value;
                    $ProductIngredient->created_by = $user_id;
                    $ProductIngredient->updated_by = $user_id;
                    $ProductIngredient->save();
                }
                
                foreach ($variant_id as $key => $value) {
                    $Productvariant = new Productvariant;
                    $Productvariant->product_id = $data->id;
                    $Productvariant->variant_id = $value;
                    $Productvariant->created_by = $user_id;
                    $Productvariant->updated_by = $user_id;
                    $Productvariant->save();
                }
                if($promotion_id){
                    foreach ($promotion_id as $key => $value) {
                        $Productvariant = new Productpromotion;
                        $Productvariant->product_id = $data->id;
                        $Productvariant->promotion_id = $value;
                        $Productvariant->created_by = $user_id;
                        $Productvariant->updated_by = $user_id;
                        $Productvariant->save();
                    }
                }
                
                $request->session()->flash('successMsg','Product Added Successfully.');
                return redirect()->route('product.index');

            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {   
        $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->orderBy('category_name','ASC')
                        ->where('status','Active')
                        ->where('parent_id','!=','')
                        ->get();

        $Ingredient = Ingredient::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('name','ASC')
                                ->get(); 

        $Variant = Variant::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('name','ASC')
                                ->get();

        $Promotion = Promotion::where('is_deleted','0')
                                ->where('status','Active')
                                ->orderBy('title','ASC')
                                ->get();

        $Productcategory = CategoryProduct::where('is_deleted','0')
                                        ->where('product_id',$product->id)
                                        ->pluck('category_id')
                                        ->toArray();

        $ProductVariant = Productvariant::where('is_deleted','0')
                                        ->where('product_id',$product->id)
                                        ->pluck('variant_id')
                                        ->toArray();

        $ProductPromotion = ProductPromotion::where('is_deleted','0')
                                        ->where('product_id',$product->id)
                                        ->pluck('promotion_id')
                                        ->toArray();

        $ProductIngredient = ProductIngredient::where('is_deleted','0')
                                        ->where('product_id',$product->id)
                                        ->pluck('ingredient_id')
                                        ->toArray();        

        return view('admin.product.productEdit',compact('product','categoryList','Ingredient','Variant','Promotion','ProductIngredient','ProductPromotion','ProductVariant','Productcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name'=>'required|max:100',
            'description'=>'required|max:9999',
            'base_price'=>'required|numeric|min:1',
            'discounted_price'=>'required|numeric|min:1',
            'weight'=>'required|numeric|min:1',
            'category_id'=>'required',
            'ingredient_id'=>'required',
            'variant_id'=>'required',
            // 'promotion_id'=>'required'
        ]);

        if($request->discounted_price>$request->base_price){
                     return back()->with([
                                        'discounted_price_greater'=>'Discounted price should be less from base price'
                                        ]);  
                }

        if($request->file('image')!=''){
            $img_path = uploadImage($request->file('image'));
            unlinkImage($request->old_image);
        }else{
                $img_path = $request->old_image;
        }
        $user_id = Auth::user()->id;
        $ingredient_id = $request->ingredient_id;
        $variant_id = $request->variant_id;
        $promotion_id = $request->promotion_id;
        $product->name = $request->name;
        $product->base_price = $request->base_price;
        $product->discounted_price = $request->discounted_price;
        $product->weight = $request->weight;
        $product->description = $request->description;
        $product->image = $img_path;
        $product->status = $request->status;
        $product->created_by = $request->user_id;
        $product->updated_by = $request->user_id;
        $product->update();
        $CategoryProduct = CategoryProduct::where('product_id',$product->id)->first();
        $CategoryProduct->category_id = $request->category_id;
        $CategoryProduct->status = $request->status;
        $CategoryProduct->updated_by = $user_id;
        $CategoryProduct->update();
        $ProductIngredient = ProductIngredient::where('product_id',$product->id)->get();
        $Productvariant = Productvariant::where('product_id',$product->id)->get();
        $ProductPromotion = ProductPromotion::where('product_id',$product->id)->get();


        foreach ($ProductIngredient as $value) {
            $value->delete();
        }
        foreach ($Productvariant as $value) {
            $value->delete();
        }
        foreach ($ProductPromotion as $value) {
            $value->delete();
        }

        foreach ($ingredient_id as $key => $value) {
            $ProductIngredient = new ProductIngredient;
            $ProductIngredient->product_id = $product->id;
            $ProductIngredient->ingredient_id = $value;
            $ProductIngredient->status = $request->status;
            $ProductIngredient->created_by = $user_id;
            $ProductIngredient->updated_by = $user_id;
            $ProductIngredient->save();
        }

        foreach ($variant_id as $key => $value) {
            $Productvariant = new Productvariant;
            $Productvariant->product_id = $product->id;
            $Productvariant->status = $request->status;
            $Productvariant->variant_id = $value;
            $Productvariant->created_by = $user_id;
            $Productvariant->updated_by = $user_id;
            $Productvariant->save();
        }
        if($promotion_id){
            foreach ($promotion_id as $key => $value) {
                $Productvariant = new Productpromotion;
                $Productvariant->product_id = $product->id;
                $Productvariant->status = $request->status;
                $Productvariant->promotion_id = $value;
                $Productvariant->created_by = $user_id;
                $Productvariant->updated_by = $user_id;
                $Productvariant->save();
            }
        }    

        $request->session()->flash('successMsg','Product Updated Successfully.');
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->is_deleted = "1";
        $product->update();
        $CategoryProduct = CategoryProduct::where('product_id',$product->id)->first();
        $CategoryProduct->is_deleted = "1";
        $CategoryProduct->update();
        $ProductIngredient = ProductIngredient::where('product_id',$product->id)->get();
        foreach ($ProductIngredient as $value) {
            $value->is_deleted = "1";
            $value->update();
        }
        $Productvariant = Productvariant::where('product_id',$product->id)->get();
        foreach ($Productvariant as $value) {
            $value->is_deleted = "1";
            $value->update();
        }
        $ProductPromotion = ProductPromotion::where('product_id',$product->id)->get();
        foreach ($ProductPromotion as $value) {
            $value->is_deleted = "1";
            $value->update();
        }

        return redirect()->route('product.index')->with('successMsg',"Product Deleted Successfully.");
    }
}

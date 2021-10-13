<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Productvariant;
use Illuminate\Http\Request;
use Auth;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page = paginationValue();
        $variantData = Variant::where('is_deleted','0')
                                ->latest('id')
                                ->paginate($page); 
        return view('admin.variant.variantList',compact('variantData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $products = Product::where('is_deleted','0')
                            ->where('status','Active')
                            ->get();
        return view('admin.variant.variantAdd',compact('products'));
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
                'name'=>'required|max:500',
                'price'=>'required|numeric|min:1',
                'unit'=>'required',
                'type'=>'required',
        ]);
        $Variant = new Variant;
        $user_id = Auth::user()->id;
        $Variant->name = $request->name;
        $Variant->price = $request->price;
        $Variant->unit = $request->unit;
        $Variant->type = $request->type;
        $Variant->created_by = $user_id;
        $Variant->updated_by = $user_id;
        $Variant->save();
        return redirect()->route('variant.index')->with('successMsg',"Variant Added Successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function edit(Variant $variant)
    {   

        $products = Product::where('is_deleted','0')
                            ->where('status','Active')
                            ->get();
        return view('admin.variant.variantEdit',compact('variant','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Variant $variant)
    {
        $validatedData = $request->validate([
                'name'=>'required|max:500',
                'price'=>'required|numeric|min:1',
                'unit'=>'required',
                'type'=>'required'
        ]);
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $variant->name = $request->name;
        $variant->price = $request->price;
        $variant->unit = $request->unit;
        $variant->type = $request->type;
        $variant->status = $request->status;
        $variant->updated_by = $user_id;
        $variant->update();
        $request->session()->flash('successMsg','Variant Updated Successfully.');
        return redirect()->route('variant.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {   
        $variant->is_deleted = "1";
        $variant->update();
        return redirect()->route('variant.index')->with('successMsg',"Variant Deleted Successfully.");
    }
}

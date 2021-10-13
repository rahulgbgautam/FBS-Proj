<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Productpromotion;
use Illuminate\Http\Request;
use Auth;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotionData = Promotion::latest('id')->paginate(10); 
        return view('admin.promotion.promotionList',compact('promotionData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $product = Product::where('is_deleted','0')
                                ->orderBy('name','ASC')
                                ->where('status','Active')
                                ->get(); 

        return view('admin.promotion.promotionAdd',compact('product'));
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
            'title'=>'required|max:100',
            'code'=>'required|max:50',
            'start_date'=>'required',
            'end_date'=>'required',
            'discount'=>'required|numeric|min:1|max:99',
            'max_allowed'=>'required|numeric|min:0|max:999999999',
            'image'=>'required|image|mimes:jpg,png,jpeg,svg'
        ]);

         if($request->file('image')){
            $user_id = Auth::user()->id;
            $img_path = uploadImage($request->file('image'));
            $data = new Promotion;
            $data->title = $request->title;
            $data->code = $request->code;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->discount = $request->discount;
            $data->max_allowed = $request->max_allowed;
            $data->image = $img_path;
            $data->created_by = $user_id;
            $data->updated_by = $user_id;
            $data->save();
            $request->session()->flash('successMsg','Promotion Added Successfully.');
            return redirect()->route('promotion.index');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit(Promotion $promotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promotion $promotion)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Auth;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Ingredient = Ingredient::where('is_deleted','0')
                                ->latest('id')->paginate(10); 
        return view('admin.ingredient.ingredientList',compact('Ingredient'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ingredient.ingredientAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // return $request->all();
        $validatedData = $request->validate([
            'name'=>'required|unique:ingredients,name|max:50',
            'image'=>'required|image|mimes:jpg,png,jpeg,svg'
        ]);

         if($request->file('image')){
            $user_id = Auth::user()->id;
            $img_path = uploadImage($request->file('image'));
            $data = new Ingredient;
            $data->name = $request->name;
            $data->image = $img_path;
            $data->created_by = $user_id;
            $data->updated_by = $user_id;
            $data->save();
            $request->session()->flash('successMsg','Ingredient Added Successfully.');
            return redirect()->route('ingredient.index');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function edit(Ingredient $ingredient)
    {
        // return $ingredient;
        return view('admin.ingredient.ingredientEdit',compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {

         if($request->name != $request->name_old && $request->name != ''){

                $validatedData = $request->validate([
                    'name'=>'required|unique:ingredients,name|max:50',
                    'image'=>'image|mimes:jpg,png,jpeg,svg'
                ]);

        }else{

                $validatedData = $request->validate([
                    'name'=>'max:200',
                    'image'=>'image|mimes:jpg,png,jpeg,svg'
                ]);
        }

        if($request->file('image')!=''){
            $img_path = uploadImage($request->file('image'));
            unlinkImage($request->old_image);
            
        }else{
                $img_path = $request->old_image;
        }

        $ingredient->name = $request->name;
        $ingredient->image = $img_path;
        $ingredient->status = $request->status;
        $ingredient->update();
        $request->session()->flash('successMsg','Ingredient Updated Successfully.');
        return redirect()->route('ingredient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
       $ingredient->is_deleted = "1";
        $ingredient->update();
        return redirect()->route('ingredient.index')->with('successMsg',"Ingredient Deleted Successfully.");
    }
}

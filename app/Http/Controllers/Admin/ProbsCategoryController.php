<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProbsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;



class ProbsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;    
        $menu_write = menuPermissionByType($id,"write");
        if(in_array("probs-category",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $content = ProbsCategory::where('is_deleted','0')
                        ->orderBy('category_name','ASC')
                        ->where('parent_id','=','')
                        ->paginate(10);  
        return view('admin.probsCategory.probsCategoryList',compact('content','action_display'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.probsCategory.probsCategoryAdd');
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
            'category_name'=>'required|unique:ds_probs_category,category_name|max:100',
                'category_image'=>'required|image|mimes:jpg,png,jpeg,svg'
        ]);
       
         if($request->file('category_image')){
            $user_id = Auth::user()->id;
            $img_path = uploadImage($request->file('category_image'));
            $data = new ProbsCategory;
            $data->category_name = $request->category_name;
            $data->category_image = $img_path;
            $data->created_by = $user_id;
            $data->updated_by = $user_id;
            $data->save();
            return redirect()->route('probs-category.index')->with('successMsg',"Category Added Successfully.");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProbsCategory $probsCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProbsCategory $probsCategory)
    {
        return view('admin.probsCategory.probsCategoryEdit',compact('probsCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProbsCategory $probsCategory)
    {   

        if($request->category_name != $request->category_name_old && $request->category_name != ''){

            $validatedData = $request->validate([
                'category_name'=>'required|unique:ds_probs_category,category_name|max:100',
                'category_image'=>'image|mimes:jpg,png,jpeg,svg'
            ]);

        }else{

            $validatedData = $request->validate([
                'category_name'=>'required|max:200',
                'category_image'=>'image|mimes:jpg,png,jpeg,svg'
            ]);

        }

        if($request->file('category_image')!=''){
            $img_path = uploadImage($request->file('category_image'));
            unlinkImage($request->old_category_image);           
        }else{
                $img_path = $request->old_category_image;
        }
        
         $user_id = Auth::user()->id;
        $probsCategory->category_name = $request->category_name;
        $probsCategory->category_image = $img_path;
        $probsCategory->status = $request->status;
        $probsCategory->updated_by = $user_id;
        $probsCategory->update();
        return redirect()->route('probs-category.index')->with('successMsg',"Category Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProbsCategory  $probsCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProbsCategory $probsCategory)
    {

        $probsCategory->is_deleted="1";
        $probsCategory->update();
        return redirect()->route('probs-category.index')->with('successMsg',"Category Deleted Successfully.");

    }
}

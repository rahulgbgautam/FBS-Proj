<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProbsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;



class ProbsSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
                         
        $id = $request->category_id;
        $admin_id = Auth::user()->id;     
        $menu_write = menuPermissionByType($admin_id,"write");
        if(in_array("probs-sub-category",$menu_write)){
            $action_display = 1;
        }else{
            $action_display = 0;
        }
        $content = ProbsCategory::where('is_deleted','0')
                        ->orderBy('category_name','ASC')
                        ->where('parent_id','!=','')
                        ->paginate(10);                                 
        if($id){
                return view('admin.probsSubCategory.probsSubCategoryList',compact('content','id','action_display'));
            }else{
                return view('admin.probsSubCategory.probsSubCategoryList',compact('content','action_display'));
        }

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
                            ->where('status','Active')
                            ->where('parent_id','=','')
                            ->orderBy('category_name','ASC')
                            ->get(); 
       return view('admin.probsSubCategory.probsSubCategoryAdd',compact('categoryList'));
        
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
                'category_id'=>'required',
                'sub_category_name'=>'required|unique:ds_probs_category,category_name|max:100',
                'category_image'=>'required|image|mimes:jpg,png,jpeg,svg'
        ]);

        if($request->file('category_image')){
            $user_id = Auth::user()->id;
            $img_path = uploadImage($request->file('category_image'));
            $data = new ProbsCategory;
            $data->parent_id = $request->category_id;
            $data->category_name = $request->sub_category_name;
            $data->category_image = $img_path;
            $data->created_by = $user_id;
            $data->updated_by = $user_id;
            $data->save();
            return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Added Successfully.");
        }            
           
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProbsCategory $probsCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $probsSubCategory = ProbsCategory::find($id);
        $selectedCategory = DB::table('ds_probs_category')
                                ->where('is_deleted','0')
                                ->where('status','Active')
                                ->where('id',$probsSubCategory->parent_id)
                                ->get();

        $categoryList = DB::table('ds_probs_category')
                        ->where('is_deleted','0')
                        ->where('status','Active')
                        ->where('parent_id','=','')
                        ->orderBy('category_name','ASC')
                        ->get();                    

        // return $probsSubCategory;
        return view('admin.probsSubCategory.probsSubCategoryEdit',compact('probsSubCategory','categoryList','selectedCategory'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        
        if($request->sub_category_name != $request->sub_category_name_old && $request->sub_category_name != ''){

                $validatedData = $request->validate([
                    'category_id'=>'required',
                    'sub_category_name'=>'required|unique:ds_probs_category,category_name|max:100',
                    'status'=>'required',
                ]);

        }else{

            $validatedData = $request->validate([
                'category_id'=>'required',
                'sub_category_name'=>'required|max:200',
                'status'=>'required',
            ]);
        }

        if($request->file('category_image')!=''){
            $img_path = uploadImage($request->file('category_image'));
            unlinkImage($request->old_category_image);           
        }else{
                $img_path = $request->old_category_image;
        }
        $user_id = Auth::user()->id;
        $probsSubCategory = ProbsCategory::find($id);
        $probsSubCategory->parent_id = $request->category_id;
        $probsSubCategory->category_name = $request->sub_category_name;
        $probsSubCategory->category_image = $img_path;
        $probsSubCategory->status = $request->status;
        $probsSubCategory->updated_by = $user_id;
        $probsSubCategory->update();
        return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Updated Successfully.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProbsSubCategory  $probsSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $probsSubCategory = ProbsCategory::find($id);
        $probsSubCategory->is_deleted = "1";
        $probsSubCategory->update();
        return redirect()->route('probs-sub-category.index')->with('successMsg',"Sub Category Deleted Successfully.");
    }
}

@extends('layouts.admin')
@section('content')
@section('probs_sub_category_select','active') 
<h5 class="text-left pl-3"> Edit Sub Category </h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-sub-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{route('probs-sub-category.update',$probsSubCategory->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Category List</label>
			<select class="form-control" name="category_id">
				@if($selectedCategory ?? '')
					@foreach($selectedCategory ?? '' as $Data)
						<option value="{{$Data->id}}" class="selected">{{$Data->category_name}}</option>
						{{ $selectedCategory = $Data->category_name }}
					@endforeach
				@endif
				@if($categoryList ?? '')                    
                    @foreach($categoryList ?? '' as $Data)        
                    	@if($Data->category_name != $selectedCategory)
                            <option value="{{$Data->id}}">{{$Data->category_name}}</option>
                        @endif                  
                    @endforeach                 
                @endif
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Name</label>
			<input type="text" name="sub_category_name" class="form-control" value="{{$probsSubCategory->category_name}}">
			<input type="hidden" name="sub_category_name_old" class="form-control" value="{{$probsSubCategory->category_name}}">
			@error('sub_category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group mb-0">
			<label>Previous Image</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($probsSubCategory->category_image)}}" style="width:100px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select New Image</label>
			<input type="file" name="category_image" class="form-control-file">
			<input type="hidden" name="old_category_image" value="{{$probsSubCategory->category_image}}">
			@error('category_image')
				{{ $message }}
			@enderror
			<label class="text-danger">Select Image (Size: 152PX, 152PX) for Best Resolution</label>
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($probsSubCategory->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($probsSubCategory->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Update Sub Category</button>
		</div>
	</form>	
</div>
@endsection
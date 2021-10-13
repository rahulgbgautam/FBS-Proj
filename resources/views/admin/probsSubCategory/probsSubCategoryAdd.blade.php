@extends('layouts.admin')
@section('content')
@section('probs_sub_category_select','active') 
<h5 class="text-left pl-3">Add Sub Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-sub-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{route('probs-sub-category.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Category List</label>
			<select class="form-control" name="category_id">
				<option value=" ">Select Category</option>
				@if($categoryList ?? '')
					@foreach($categoryList ?? '' as $Data)
						<option value="{{$Data->id}}" value="{{old('category_id')}}" @if(old('category_id')== $Data->id) selected="selected" @endif>{{$Data->category_name}}</option>
					@endforeach
				@endif
			</select>
			@error('category_id')
				<span class="text-danger" role="alert">
					<strong>The category is required</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Sub Category Name</label>
			<input type="text" name="sub_category_name" class="form-control" value="{{old('sub_category_name')}}">
			@error('sub_category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Select Sub Category Image</label>
			<input type="file" name="category_image" class="form-control-file"  value="{{old('category_image')}}">
			@error('category_image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	        <label class="text-danger">Select Image (Size: 152PX, 152PX) for Best Resolution</label>
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Sub Category</button>
		</div>
	</form>	
</div>
@endsection
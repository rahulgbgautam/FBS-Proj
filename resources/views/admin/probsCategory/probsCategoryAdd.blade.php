@extends('layouts.admin')
@section('content')
@section('probs_category_select','active') 
<h5 class="text-left pl-3">Add Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('probs-category.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Category Name</label>
			<input type="text" name="category_name" class="form-control" value="{{old('category_name')}}">
			@error('category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Select Category Image</label>
			<input type="file" name="category_image" class="form-control-file"  value="{{old('category_image')}}">
			@error('category_image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	        <label class="text-danger">Select Image (Size: 152PX, 152PX) for Best Resolution</label>
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Category</button>
		</div>
	</form>	
</div>
@endsection
@extends('layouts.admin')
@section('content')
@section('probs_category_select','active') 
<h5 class="text-left pl-3">Edit Category</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('probs-category.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('probs-category.update',$probsCategory->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Category Name</label>
			<input type="text" name="category_name" class="form-control" value="{{old('category_name',$probsCategory->category_name)}}">
			<input type="hidden" name="category_name_old" class="form-control" value="{{$probsCategory->category_name}}">
			@error('category_name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group mb-0">
			<label>Previous Image</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($probsCategory->category_image)}}" style="width:100px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select New Image</label>
			<input type="file" name="category_image" class="form-control-file">
			<input type="hidden" name="old_category_image" value="{{$probsCategory->category_image}}">
			@error('category_image')
				{{ $message }}
			@enderror
			<label class="text-danger">Select Image (Size: 152PX, 152PX) for Best Resolution</label>
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($probsCategory->status == "Active") selected="selected" @endif> Active </option>
				<option value="Inactive" @if($probsCategory->status == "Inactive") selected="selected" @endif> Inactive </option>
			</select>
			@error('status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
</div>
@endsection


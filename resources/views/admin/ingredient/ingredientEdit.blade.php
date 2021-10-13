@extends('layouts.admin')
@section('content')
@section('ingredient_select','active')			
<h5 class="text-left pl-3">Edit ingredient</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('ingredient.index'))}}" class="btn btn-primary">Back</a></div>
</div> 
<div class="col-8">
	@if($ingredient)
	<form action="{{route('ingredient.update',$ingredient->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{$ingredient->name}}">
			<input type="hidden" name="name_old" class="form-control" value="{{$ingredient->name}}">
			@error('name')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group mb-0">
			<label>Previous ingredient</label>
		</div>
		<div class="form-group">
			<img src="{{showImage($ingredient->image)}}" style="width:200px;height: auto;">
		</div>
		<div class="form-group">
			<label>Select New ingredient</label>
			<input type="file" name="image" class="form-control-file">
			<input type="hidden" name="old_image" value="{{$ingredient->image}}">
			@error('image')
				{{ $message }}
			@enderror
			<label class="text-danger">Select Image (Size: 51PX, 51PX) for Best Resolution</label>
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($ingredient->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive"  @if( $ingredient->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>	
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update ingredient</button>
		</div>
	</form>	
	@endif			
</div>
@endsection

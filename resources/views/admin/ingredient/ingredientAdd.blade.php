@extends('layouts.admin')
@section('content')
@section('ingredient_select','active')			
<h5 class="text-left pl-3">Add Ingredient</h5>
<!-- @if(session()->has('password_not_match'))
<span class="text-danger" role="alert">
	<strong>{{session('password_not_match')}}</strong>
</span>
@endif -->
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('ingredient.index'))}}" class="btn btn-primary">Back</a></div>
</div>  
<div class="col-8">
	<form action="{{route('ingredient.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{old('name')}}">
			@error('name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Select Image</label>
			<input type="file" name="image" class="form-control-file"  value="{{old('image')}}">
			@error('image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
	        <label class="text-danger">Select Image (Size: 51PX, 51PX) for Best Resolution</label>
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Ingredient</button>
		</div>
	</form>	
</div>
@endsection
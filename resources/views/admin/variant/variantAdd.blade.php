@extends('layouts.admin')
@section('content')		
@section('variant_select','active')	
<h5 class="text-left pl-3">Add Variant</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('variant.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('variant.store')}}" method="post" enctype="multipart/form-data">
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
			<label>Price($)</label>
			<input type="text" name="price" class="form-control" value="{{old('price')}}">
			@error('price')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Unit</label>
			<input type="text" name="unit" class="form-control" value="{{old('unit')}}">
			@error('unit')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
	    	<label>Type</label>
	    	<select class="form-control" name="type" id="baner_types" required>
	    		<option value="size">Size</option>
	    		<option value="weight">Weight</option>
	    		<option value="ingredient">Ingredient</option>
	    		<option value="dressing">Dressing</option>
	    		<option value="toppings">Toppings</option>
	    		<option value="greens">Greens</option>
	    		<option value="base">Base</option>
	    		<option value="proteins">Proteins</option>
	    		<option value="drinks">Drinks</option>
	    	</select>
	    </div>
		<!-- <div class="form-group">
			<label>Select Image</label>
			<input type="file" name="image" class="form-control-file"  value="{{old('image')}}">
			@error('image')
	            <span class="text-danger" role="alert">
	                <strong>{{$message}}</strong>
	            </span>
	        @enderror
		</div> -->
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Variant</button>
		</div>
	</form>	
</div>
@endsection
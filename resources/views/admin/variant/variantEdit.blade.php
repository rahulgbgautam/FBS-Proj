@extends('layouts.admin')
@section('content')
@section('variant_select','active')			
<h5 class="text-left pl-3">Edit Variant</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('variant.index'))}}" class="btn btn-primary">Back</a></div>
</div> 
<div class="col-8">
	@if($variant)
	<form action="{{route('variant.update',$variant->id)}}" method="post" enctype="multipart/form-data">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control" value="{{$variant->name}}">
			@error('name')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Price($)</label>
			<input type="text" name="price" class="form-control" value="{{$variant->price}}">
			@error('price')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Unit</label>
			<input type="text" name="unit" class="form-control" value="{{$variant->unit}}">
			@error('unit')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
	    	<label>Type</label>
	    	<select class="form-control" name="type" required>
	    		<option value="size" @if($variant->type == "size") selected @endif>Size</option>
	    		<option value="weight" @if($variant->type == "weight") selected @endif>Weight</option>
	    		<option value="ingredient" @if($variant->type == "ingredient") selected @endif>Ingredient</option>
	    		<option value="dressing" @if($variant->type == "dressing") selected @endif>Dressing</option>
	    		<option value="toppings" @if($variant->type == "toppings") selected @endif>Toppings</option>
	    		<option value="greens" @if($variant->type == "greens") selected @endif>Greens</option>
	    		<option value="base" @if($variant->type == "base") selected @endif>Base</option>
	    		<option value="proteins" @if($variant->type == "proteins") selected @endif>Proteins</option>
	    		<option value="drinks" @if($variant->type == "drinks") selected @endif>Drinks</option>
	    	</select>
	    </div>
	    <div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($variant->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive"  @if($variant->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('Status')
				<span class="text-danger" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>	
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update variant</button>
		</div>
	</form>	
	@endif			
</div>
@endsection

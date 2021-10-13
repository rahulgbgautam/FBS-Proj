@extends('layouts.admin')
@section('content')		
@section('product_select','active')	
<h5 class="text-left pl-3">Add Product</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('product.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('product.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Sub Category List</label>
			<select class="form-control" name="category_id">
				<option value=" ">Select Category</option>
				@if($categoryList ?? '')
					@foreach($categoryList ?? '' as $Data)
						<option value="{{$Data->id}}" @if(old('category_id')== $Data->id) selected="selected" @endif>{{$Data->category_name}}</option>
					@endforeach
				@endif
			</select>
			@error('category_id')
				<span class="text-danger" role="alert">
					<strong>The category is required.</strong>
				</span>
			@enderror
		</div>
		@if($Ingredient ?? '')
			<div class="form-group">
				<label>Ingredient List</label>
				<select class="form-control" name="ingredient_id[]" multiple>
						{{$i=0}}
						@foreach($Ingredient ?? ' ' as $data)
							<option value="{{$data->id}}" @if(old('ingredient_id.'.$i)== $data->id) selected="selected" @endif>{{$data->name}}</option>
						{{$i=$i+1}}
						@endforeach
				</select>
				@error('ingredient_id')
					<span class="text-danger" role="alert">
						<strong>The ingredient is required.</strong>
					</span>
				@enderror
			</div>
		@endif
		@if($Variant ?? '')
			<div class="form-group">
				<label>Variant List</label>
				<select class="form-control" name="variant_id[]" multiple>
						{{$i=0}}
						@foreach($Variant ?? ' ' as $data)
							<option value="{{$data->id}}" @if(old('variant_id.'.$i)== $data->id) selected="selected" @endif>{{$data->name}}</option>
						{{$i=$i+1}}
						@endforeach
				</select>
				@error('variant_id')
					<span class="text-danger" role="alert">
						<strong>The variant is required.</strong>
					</span>
				@enderror
			</div>
		@endif
		@if($Promotion ?? '')
			<div class="form-group">
				<label>Promotion List</label>
				<select class="form-control" name="promotion_id[]" multiple>
						{{$i=0}}
						@foreach($Promotion ?? ' ' as $data)
							<option value="{{$data->id}}" @if(old('promotion_id.'.$i)== $data->id) selected="selected" @endif>{{$data->title}}</option>
						{{$i=$i+1}}
						@endforeach
				</select>
				@error('promotion_id')
					<span class="text-danger" role="alert">
						<strong>{{$message}}</strong>
					</span>
				@enderror
			</div>
		@endif
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
			<label>Base Price($)</label>
			<input type="text" name="base_price" class="form-control" value="{{old('base_price')}}">
			@error('base_price')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Discounted Price($)</label>
			<input type="text" name="discounted_price" class="form-control" value="{{old('discounted_price')}}">
			@error('discounted_price')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
			@if(Session::get('discounted_price_greater'))
			<span class="text-danger" role="alert">
			  <strong>The discounted price should be less from base price</strong>
			 </span>
			@endif  
		</div>
		<div class="form-group">
			<label>Weight(Gram)</label>
			<input type="text" name="weight" class="form-control" value="{{old('weight')}}">
			@error('weight')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Description</label>
			<textarea  name="description" class="form-control">{{old('description')}}</textarea>
			@error('description')
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
		<label class="text-danger">Select Image (Size: 375PX, 375PX) for Best Resolution</label>
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Product</button>
		</div>
	</form>	
</div>
@endsection
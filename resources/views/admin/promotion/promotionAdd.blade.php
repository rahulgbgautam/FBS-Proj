@extends('layouts.admin')
@section('content')		
@section('promotion_select','active')	
<h5 class="text-left pl-3">Add Promotion</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('promotion.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	<form action="{{route('promotion.store')}}" method="post" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="{{old('title')}}">
			@error('title')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Code</label>
			<input type="text" name="code" class="form-control" value="{{old('code')}}">
			@error('code')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Start Date</label>
			<input type="date" name="start_date" class="form-control" value="{{old('start_date')}}">
			@error('start_date')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>End Date</label>
			<input type="date" name="end_date" class="form-control" value="{{old('end_date')}}">
			@error('end_date')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Discount(%)</label>
			<input type="text" name="discount" class="form-control" value="{{old('discount')}}">
			@error('discount')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Max Allowed</label>
			<input type="text" name="max_allowed" class="form-control" value="{{old('max_allowed')}}">
			@error('max_allowed')
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
	        <label class="text-danger">Select Image (Size: 375PX, 180PX) for Best Resolution</label>
		</div>
		<div class="text-left mb-4">
			<button type="submit" class="btn btn-success">Add Promotion</button>
		</div>
	</form>	
</div>
@endsection
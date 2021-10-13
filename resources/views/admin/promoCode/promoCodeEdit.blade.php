@extends('layouts.admin')
@section('content')
@section('promo_code_select','active')	
<h5 class="text-left pl-3">Edit Promo Code</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('promo-code.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-6">
	@if($promoCode)
	<form action="{{route('promo-code.update',$promoCode->id)}}" method="post">
		@csrf
		@method('put')
		<div class="form-group">
			<label>Promo Code</label>
			<input type="text" name="promo_code" class="form-control" value="{{$promoCode->promo_code}}">
			<input type="hidden" name="promo_code_old" class="form-control" value="{{$promoCode->promo_code}}">
			<label class="text-danger">Please Enter Promo Code Without Space</label>
			@error('promo_code')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Available</label>
			<input type="text" name="available" class="form-control" value="{{$promoCode->available}}">
			@error('available')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Expire Date</label>
			<input type="date" name="expire_date" class="form-control" value="{{$promoCode->expire_date}}">
			@error('expire_date')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Discount (In Percent)</label>
			<input type="text" name="discount" class="form-control" value="{{$promoCode->discount}}">
			@error('discount')
			<span class="text-danger" role="alert">
				<strong>{{$message}}</strong>
			</span>
			@enderror
		</div>
		<div class="form-group">
			<label>Status</label>
			<select class="form-control" name="status">
				<option value="Active" @if($promoCode->status == "Active") selected="selected" @endif>Active</option>
				<option value="Inactive" @if($promoCode->status == "Inactive") selected="selected" @endif>Inactive</option>
			</select>
			@error('status')
				<span class="text-danger" role="alert">
					<strong>{{$message}}</strong>
				</span>
			@enderror
		</div>
		<div class="text-left">
			<button type="submit" class="btn btn-success">Update</button>
		</div>
	</form>	
	@endif			
</div>
@endsection

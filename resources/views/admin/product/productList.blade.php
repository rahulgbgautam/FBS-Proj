@extends('layouts.admin')
@section('content')
@section('product_select','active')           
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Product</h4>
    <!-- @if($action_display ?? ' ') -->
        <div class="col-md-6 ml-auto">
                <form class="form-inline head-form-box" id="myform" method="get">
                    <div class="form-group head_search">
                      <input type="text" class="form-control" name="search" placeholder="Search By Product Name" id="myInputs"  value="{{old('search',$search)}}">
                      <div class="input-group-append">
                        <button type=submit class="btn btn-success"><i class="fa fa-search"></i> </button>
                      </div>
                    </div>
                </form>
            </div>
        <div class=""><a href="{{url(route('product.create'))}}" class="btn btn-primary">Add Product</a></div>
    <!-- @endif -->
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Image</th>
                    <th class="">Name</th>
                    <th class="">Base Price($)</th>
                    <th class="">Discounted Price($)</th>
                    <th class="">Weight(Gram)</th>
                    <th class="">Description</th>
                    <th class="">Status</th>
                    <!-- @if($action_display ?? ' ') -->
                    <th class="">Action(s)</th>
                    <!-- @endif -->
                </tr>
            </thead>
            <tbody>
                @if($ProductData ?? '')
                    @foreach($ProductData ?? '' as $Data) 
                        <tr>
                            <td class="rating-list p-2"><img src="{{showImage($Data->image)}}" style="width: 100px;height: auto;"> </td>
                            <td class="rating-list"><span>{{$Data->name}}</span></td>
                            <td class="rating-list"><span>{{$Data->base_price}}</span></td>
                            <td class="rating-list"><span>{{$Data->discounted_price}}</span></td>
                            <td class="rating-list"><span>{{$Data->weight}}</span></td>
                            <td class="rating-list"><span>{{$Data->description}}</span></td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            <!-- @if($action_display ?? ' ') -->
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('product.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                    <form action="{{url(route('product.destroy',$Data->id))}}" method="POST"> 
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="text-primary" onclick=" return confirm('Are you sure want to delete this record?');" data-toggle="tooltip"  title="Delete"><img src="{{asset('img/blue-delete-icon.svg')}}" alt="Delete Icon" /></button>
                                    </form>  
                                </td>
                            <!-- @endif     -->
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($ProductData) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{$ProductData->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection

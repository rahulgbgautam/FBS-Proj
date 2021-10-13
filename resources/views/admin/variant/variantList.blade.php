@extends('layouts.admin')
@section('content')
@section('variant_select','active')           
<div class="admin-head d-flex align-items-center justify-content-between pb-3">
    <h4 class="content-head">Variant</h4>
    <!-- @if($action_display ?? ' ') -->
        <div class=""><a href="{{url(route('variant.create'))}}" class="btn btn-primary">Add Variant</a></div>
    <!-- @endif -->
</div>
<!-- My Brands Section HTML -->
<div class="my-brand-section overflow-hidden">
    <div class="table-responsive">
        <table id="dataTableExample" class="table">
            <thead>
                <tr>
                    <th class="">Name</th>
                    <th class="">Price($)</th>
                    <th class="">Unit</th>
                    <th class="">Type</th>
                    <th class="">Status </th>
                    <!-- @if($action_display ?? ' ') -->
                    <th class="">Action(s)</th>
                    <!-- @endif -->
                </tr>
            </thead>
            <tbody>
                @if($variantData ?? '')
                    @foreach($variantData ?? '' as $Data) 
                        <tr>
                            <td class="rating-list"><span>{{ucWords($Data->name)}}</span></td>
                            <td class="rating-list">{{$Data->price}}</td>
                            <td class="rating-list">{{$Data->unit}}</td>
                            <td class="rating-list">{{ucWords($Data->type)}}</td>
                            <td class="rating-list" width="80px;">{{$Data->status}}</td>
                            @if($action_display ?? ' ')
                                <td class="action-button" width="150px;">
                                    <a href="{{url(route('variant.edit',$Data->id))}}" class="text-primary" data-toggle="tooltip"  title="Edit">
                                        <img src="{{asset('img/blue-edit-icon.svg')}}" alt="Edit Icon" />
                                    </a>   
                                    <form action="{{url(route('variant.destroy',$Data->id))}}" method="POST"> 
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="text-primary" onclick=" return confirm('Are you sure want to delete this record?');" data-toggle="tooltip"  title="Delete"><img src="{{asset('img/blue-delete-icon.svg')}}" alt="Delete Icon" /></button>
                                    </form>  
                                </td>
                            @endif    
                        </tr>
                    @endforeach
               @endif
            </tbody>
        </table>
        @if(count($variantData) < 1) 
            <div class="no-record">No record found.</div>
        @endif
        <div class="pagination">{{$variantData->links()}}</div>
    </div>
</div>
<!-- /.My Brands Section HTML -->
@endsection

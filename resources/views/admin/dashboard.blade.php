@extends('layouts.admin')
@section('content')
@section('dashboard_select','active') 
<div class="admin-dashboard-section overflow-hidden">
    <div class="report-box d-flex align-items-stretch justify-content-between">
        <div class="list-box domain-list">
            <h4>Total Transaction Amount</h4>
        </div>
        <div class="list-box text-center">
            <h4>${{number_format($totalTransactionAmount,2)}}</h4>
        </div>
    </div>
    <div class="report-box d-flex align-items-stretch justify-content-between">
        <div class="list-box domain-list">
            <h4>Section Name</h4>
            <ul class="m-0 p-0">
                <li class="p-0">Portal Users</li>
                <li class="p-0"> </li>
<!--                 <li class="p-0">Purchased Subscription</li>
                <li class="p-0">Purchased Monthly Credits</li>
                <li class="p-0">Purchased Yearly Credits</li> -->
                <!-- <li class="p-0 mt-5 mb-5"> </li> -->
                <li class="p-0">Admin Users</li>
            </ul>
        </div>
        <div class="list-box text-center">
            <h4>Count</h4>
            <ul class="m-0 p-0">
                <li>{{$userCount}}</li>
                <li class=""> </li>
<!--                 <li>{{$MembershipCount}}</li>
                <li>{{$MonthlyCount}}</li>
                <li>{{$YearlyCount}}</li> -->
                <li class="">{{$adminUserCount}}</li>
            </ul>
        </div>
         <div class="list-box text-center">
            @if($user_action_display ||$admin_action_display)
                <h4>Action</h4>
            @endif
            <ul class="m-0 p-0">
                @if($user_action_display)
                    <li class="action-button">
                        <a href="{{url(route('user-management.index'))}}">
                            <img src="{{asset('img/blue-view-icon.svg')}}" alt="View Icon" />
                        </a>
                    </li>
                @endif
                <li class=""> </li>
                <!-- <li class="action-button">
                    <a href="{{url(route('transaction-history.index').'?transaction_type=Membership')}}">
                        <img src="{{asset('img/blue-view-icon.svg')}}" alt="View Icon" />
                    </a>
                </li>
                <li class="action-button">
                    <a href="{{url(route('transaction-history.index').'?transaction_type=Monthly')}}">
                        <img src="{{asset('img/blue-view-icon.svg')}}" alt="View Icon" />
                    </a>
                </li>
                <li class="action-button">
                    <a href="{{url(route('transaction-history.index').'?transaction_type=Yearly')}}">
                        <img src="{{asset('img/blue-view-icon.svg')}}" alt="View Icon" />
                    </a>
                </li> -->
                <!-- <li class="mt-5 mb-5"> </li> -->
                @if($admin_action_display)
                    <li class="action-button">
                        <a href="{{url(route('admin-management.index'))}}">
                            <img src="{{asset('img/blue-view-icon.svg')}}" alt="View Icon" />
                        </a>
                    </li> 
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /.Dashboard Section HTML -->
</div>
<!-- Main Content HTML End Here -->
@endsection
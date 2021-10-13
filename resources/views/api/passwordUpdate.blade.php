@extends('layouts.adminAuth')
@section('content')
<div class="main-wrapper">
        <div class="login-signup-box thankyou-page d-flex justify-content-between">
            <div class="form-box">
                <form>
                    @csrf
                    <div class="form-head">
                        <h1><a href="{{url('#')}}"><img src="{{ asset('images/logo.png') }}" alt="Logo" /></a></h1>
                        <div class="cotnent">
                            @if($verified ?? '')
                                <img src="{{ asset('img/blue-checked-icon.svg')}}" alt="    Checked Icon">
                        	   <p>Password updated successfully.</p>
                            @else
                            <p>Link Expired</p>
                            @endif   
                        </div>
                    </div>

                </form>
            </div>
            <div class="form-image-box">
                <img src="{{ asset('images/login-banner.png') }}" alt="Login Images" />
            </div>
        </div>
    </div>
@endsection

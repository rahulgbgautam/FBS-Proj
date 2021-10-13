<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//////////////////////////   BR1 ////////////////////////////////////


Route::post('/sign-up','API\AuthController@signUp');
Route::get('email-verification/{id}','API\AuthController@emailVerification');
Route::post('/otp-verification/{id}','API\AuthController@otpVerification');
Route::post('/login','API\AuthController@login');
Route::post('/forgot-password','API\AuthController@forgotPassword');
// Route::get('/reset_password/{id}','API\AuthController@reset_password');
// Route::post('reset-password-process/{id}','API\AuthController@reset_password_process');
Route::get('/get/{id}','API\AuthController@getData');
Route::get('/logout','API\AuthController@logout');
Route::get('/resend-otp/{id}','API\AuthController@resendOtp');
// Route::get('/resend-email/{id}','API\AuthController@resendEmail');


/////////////////////    BR2  //////////////////////////


Route::post('/home-page','API\HomeController@getData');
Route::post('/probs-sub-category','API\HomeController@probsSubCateogry');
Route::get('/parents-info/{id}','API\HomeController@getParentInfo');
Route::post('/product-info','API\HomeController@productInfo');
Route::post('/all-product-info','API\HomeController@allProductInfo');
Route::get('/all-variant-info/{id}','API\HomeController@allVariantInfo');
Route::post('/favourite','API\HomeController@favourite');
Route::post('/get-favourite-list','API\HomeController@getFavouriteList');
Route::post('/recently-viewed','API\HomeController@recentlyViewed');
Route::get('/customize','API\HomeController@customize');
Route::post('/search','API\HomeController@search');
Route::get('/filter','API\FilterController@getFilter');
Route::post('/apply-filter','API\FilterController@applyFilter');
Route::post('/see-similar','API\FilterController@seeSimilar');
Route::post('/product-quantity','API\HomeController@productQuantity');
Route::post('/get-product-quantity','API\HomeController@getProductQuantity');


// Route::post('/recommended','API\HomeController@recommended');





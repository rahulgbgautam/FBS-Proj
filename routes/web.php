<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// #####################  Routes ####################


// ##################### Admin Routes ####################

Route::get('/',function(){
	return "Hello";
});


// Route::get('/google','API\AuthController@redirectGoogle');
// Route::get('/google/callback','API\AuthController@handleGoogleCallback');





Route::get('reset_password/{id}','API\AuthController@reset_password');

Route::post('reset-password-process/{id}','API\AuthController@reset_password_process');

Route::get('admin','Admin\AdminController@index');
Route::get('generate-invoice/{id}', 'Front\InvoicePdfController@createPDF');

Route::group(['prefix' => 'admin'], function () {
    
    Route::view('forgot-password','admin.auth.forgotPassword');
	Route::post('forgot-password','Admin\AdminController@forgotPassword');
	Route::get('reset_password/{id}','Admin\AdminController@reset_password');
	Route::post('reset-password-process/{id}','Admin\AdminController@reset_password_process');
    Route::post('/login','Admin\AdminController@login');
	
	Route::group(["middleware"=>['admin_auth']],function(){

		Route::get('dashboard','Admin\AdminController@dashboard');
		Route::get('profile','Admin\AdminController@profile');
		Route::get("profile/edit/{id}",'Admin\AdminController@edit');
		Route::put("profile/update/{id}",'Admin\AdminController@update');
		Route::get("profile/change-password/{id}",'Admin\AdminController@profile_change_password');
		Route::put("profile/change-password-process/{id}",'Admin\AdminController@profile_change_password_process');
		Route::get('logout','Admin\AdminController@logout');
		Route::Resource('email-management','Admin\EmailTemplateController');
		Route::Resource('admin-management','Admin\AdminManagementController');
		Route::get('admin-management/createPermission/{id}','Admin\AdminManagementController@createPermission');
		Route::post('admin-management/Permissions/store/{id}','Admin\AdminManagementController@permissionsStore');
		Route::Resource('user-management','Admin\UserManagementController');
		Route::Resource('content-management','Admin\ContentManagementController');
		Route::Resource('banner-management','Admin\BannerController');
		Route::Resource('faq','Admin\FaqController');
		Route::Resource('settings','Admin\SettingsController');
		Route::Resource('dynamic-content','Admin\DynamicContentController');
		Route::Resource('probs-category','Admin\ProbsCategoryController');
		Route::Resource('probs-sub-category','Admin\ProbsSubCategoryController');
		Route::Resource('transaction-history','Admin\UserSubscriptionController');
		Route::Resource('promotion','Admin\PromotionController');
		Route::Resource('product','Admin\ProductController');
		Route::Resource('variant','Admin\VariantController');
		Route::Resource('ingredient','Admin\IngredientController');
	});
	
});
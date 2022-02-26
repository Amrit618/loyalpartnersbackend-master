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
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('changePw', 'UserController@changePassword');
});
Route::get('unverifiedUsers', 'UserController@allusers');
Route::get('verifyUsers/{id}','UserController@verifyUsers');
Route::get('deleteUsers/{id}','UserController@deleteUsers');


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::post('addproperty', 'PropertyController@createProperty');
Route::post('propertylist/{id}','PropertListController@createPropertyList');
//Property Delete
Route::get('deleteProp/{id}','PropertyController@hideProperty');
Route::group(['middleware' => 'auth:api'], function () {
});
Route::post('propertylistitem/{id}','PropertyItemsController@createPropertyItems');
Route::get('property', 'PropertyController@getProperty');
// Route::get('propertyreport/{id}', 'PropertyController@propertyReport');
Route::get('propertylist/{id}', 'PropertListController@getPropertyList');

//PROPERTY LIST ITEM DETAILS
Route::get('propertylistitem/{id}', 'PropertyItemsController@getPropertyItems');
Route::delete('propertylist/{id}','PropertListController@deletePropertyList');
Route::delete('propertylistitem/{id}', 'PropertyItemsController@deleteProperty');
Route::post('propertylistitemupdate/{id}','PropertyItemsController@updateProperty' );
Route::Post('propertyitemimage','PropertyImageController@addImage');
Route::Post('updateProperty/{id}','PropertyController@updateProperty');

//DELETE IMAGE
Route::DELETE('deleteImage/{id}','PropertyImageController@deleteImage');

//TENANT INFORMATION
Route::post('tenantinfo','TenantController@createTenant');
Route::get('tenantinfo/{id}','TenantController@getTenant');
Route::get('pdf','TenantController@test');

//PROPERTY REVIEW
Route::get('propertyreview/{id}','PropertyReviewController@getReview');
Route::post('propertyreview/{id}','PropertyReviewController@saveReview');

Route::post('forgetpassword','ForgetPassword@sendMail');


Route::group(['middleware' => 'auth:api'], function () {
    //GENERATE INSPECTION DATE
    Route::get('propertyreport/{id}', 'PropertyController@propertyReport');
    Route::post('generateinspection','InspectionController@generateInspection');
    Route::get('myinspection','InspectionController@getInspection');

    Route::get('getReportsManager', 'PropertyController@getReportsManager');
    Route::get('getReportsOwner', 'PropertyController@getReportsOwner');

});
Route::get('updateinspection/{id}','InspectionController@completeInspection');

//UPDATE OWNER EMAIL
Route::post('updateOwner/{id}','UserController@updateUser');

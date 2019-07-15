<?php

use Illuminate\Http\Request;

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

Route::get('/mobile/{mobile}', 'Controller@verifyMobile');
Route::get('/rmobile/{mobile}', 'Controller@rVerifyMobile');
Route::get('/verify/{mobile}/{verify_code}', 'Controller@verifyCode');
Route::post('/changepass', 'Controller@resetPassword');

Route::post('/login', 'Controller@apilogin');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('test', 'ApplicationController@test');
    Route::post('update_field', 'ApplicationController@updateResidentField');
    Route::post('update_self_field', 'ApplicationController@updateSelfField');
    Route::post('update_coin', 'ApplicationController@updateResidentCoin');
    Route::get('get_self_leaderboard', 'ApplicationController@getSelfLeaderboard');
    Route::get('field_list', 'ApplicationController@fieldList');
    Route::post('resident', 'ApplicationController@residentProperties');
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

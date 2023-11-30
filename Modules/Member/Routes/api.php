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

Route::get('member', 'MemberController@getMember');
Route::post('member/upload-file', 'MemberController@uploadFile');
Route::post('member/import', 'MemberController@importMember');
Route::get('google-search-api', 'MemberController@getResultSearchApi');
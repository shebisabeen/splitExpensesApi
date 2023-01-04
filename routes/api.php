<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psy\CodeCleaner\FunctionContextPass;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::get('groupmembers/fetchGroupMembers/{id}', 'GroupMemberController@fetchGroupMembers');
    Route::get('groupmembers/fetchGroupsByUserId/{id}', 'GroupMemberController@fetchGroupsByUserId');
    Route::post('groups/createGroup', 'GroupController@createGroup');
    Route::post('groupmembers/createGroupMember', 'GroupMemberController@createGroupMember');
    Route::get('groupmembers/getBalances/{id}', 'GroupMemberController@getBalances');
    Route::post('expenses/createExpense', 'ExpenseController@createExpense');
    Route::get('expenses/expensesDetailsByGroup/{id}', 'ExpenseController@expensesDetailsByGroup');
    Route::apiResource('appusers', AppUserController::class);
    Route::apiResource('groups', GroupController::class);
    Route::apiResource('groupmembers', GroupMemberController::class);
    Route::apiResource('expenses', ExpenseController::class);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentAgentController;
use App\Http\Controllers\TicketController;

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

Route::get('/', function () {
    return Redirect()->route('login');
});

/*registration and auth*/
Route::get('login',         [UserController::class, 'index'])               ->name('login');
Route::post('login',        [UserController::class, 'login'])               ->name('login.post');
Route::get('registration',  [UserController::class, 'registration'])        ->name('register');
Route::post('registration', [UserController::class, 'storeRegistration'])   ->name('register.post');
Route::get('signout',       [UserController::class, 'signOut'])             ->name('signout');
/*registration and auth finish*/

Route::get('dashboard',         [UserController::class, 'dashboard'])   ->name('dashboard');

/*admin pages*/
Route::group([
    'middleware' => ['auth', 'is.admin']
], function(){
    Route::get('user/list',             [UserController::class, 'list'])        ->name('admin.user.list');
    Route::get('user/add',              [UserController::class, 'add'])         ->name('admin.user.add');
    Route::post('user/store',           [UserController::class, 'storeUser'])   ->name('admin.user.store');
    Route::get('user/{id}/edit',        [UserController::class, 'editUser'])    ->name('admin.user.edit')
        ->whereNumber('id');
    Route::patch('user/{id}/edit',      [UserController::class, 'patchUser'])   ->name('admin.user.patch')
        ->whereNumber('id');
    Route::delete('user/{id}/delete',   [UserController::class, 'deleteUser'])  ->name('admin.user.delete')
        ->whereNumber('id');

    Route::get('department/list',           [DepartmentController::class, 'list'])      ->name('admin.department.list');
    Route::get('department/add',            [DepartmentController::class, 'add'])       ->name('admin.department.add');
    Route::post('department/store',         [DepartmentController::class , 'store'])    ->name('admin.department.store');
    Route::get('department/{id}/edit',      [DepartmentController::class, 'edit'])      ->name('admin.department.edit')
        ->whereNumber('id');
    Route::patch('department/{id}/edit',    [DepartmentController::class, 'patch'])     ->name('admin.department.patch')
        ->whereNumber('id');
    Route::delete('department/{id}/delete', [DepartmentController::class, 'delete'])    ->name('admin.department.delete')
        ->whereNumber('id');

    Route::get('department/{department_id}/agent/list', [DepartmentAgentController::class, 'list'])
        ->name('admin.department.agent.list')   ->whereNumber('department_id');
    Route::post('department/{department_id}/agent/store', [DepartmentAgentController::class, 'store'])
        ->name('admin.department.agent.store')  ->whereNumber('department_id');
    Route::delete('department/{department_id}/agent/{user_id}/delete', [DepartmentAgentController::class, 'delete'])
        ->name('admin.department.agent.delete') ->whereNumber('department_id')->whereNumber('user_id');
});
/*admin pages finish*/

/*agent pages*/
Route::group([
    'middleware' => ['auth','is.agent']
], function(){
    Route::get('agent/ticket/list',                 [TicketController::class, 'agentList'])
        ->name('agent.ticket.list');
    Route::get('agent/ticket/{id}/view',            [TicketController::class, 'agentView'])
        ->name('agent.ticket.view')             ->whereNumber('id');
    Route::post('agent/ticket/{id}/message/store',  [TicketController::class, 'agentMessageStore'])
        ->name('agent.ticket.message.store')    ->whereNumber('id');
});
/*agent pages finish*/

/*customer pages*/
Route::group([
    'middleware' => ['auth', 'is.customer']
], function(){
    Route::get('customer/ticket/list',                  [TicketController::class, 'customerList'])
        ->name('customer.ticket.list');
    Route::get('customer/ticket/add',                   [TicketController::class, 'customerAdd'])
        ->name('customer.ticket.add');
    Route::post('customer/ticker/store',                [TicketController::class, 'customerStore'])
        ->name('customer.ticket.store');
    Route::get('customer/ticket/{id}/view',             [TicketController::class, 'customerView'])
        ->name('customer.ticket.view')          ->whereNumber('id');
    Route::post('customer/ticket/{id}/message/store',   [TicketController::class, 'customerMessageStore'])
        ->name('customer.ticket.message.store') ->whereNumber('id');
});
/*customer pages finish*/

/*guest pages*/
/*guest pages finish*/

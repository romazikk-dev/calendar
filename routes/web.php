<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DefaultController as DashboardDefaultController;
use App\Http\Controllers\Dashboard\WorkerController as DashboardWorkerController;
use App\Http\Controllers\WorkerDashboard\DefaultController as WorkerDashboardDefaultController;
use App\Http\Controllers\Dashboard\HallController as DashboardHallController;
use App\Http\Controllers\Dashboard\TemplateController as DashboardTemplateController;
use App\Http\Controllers\Dashboard\ClientController as DashboardClientController;
use App\Http\Controllers\Dashboard\SettingsController as DashboardSettingsController;

use App\Http\Controllers\Calendar\BookingController as CalendarBookingController;

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
    // return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// Route::get('/dashboard', [AuthenticatedSessionController::class, 'create'])
//     ->middleware(['auth'])
//     ->name('dashboard');


Route::get('/worker-dashboard', [WorkerDashboardDefaultController::class, 'index'])
    ->middleware(['auth:worker'])
    ->name('index');
    

// Route::middleware(['auth'])->group([
Route::group([
    'middleware' => ['auth'],
    'prefix' => '/dashboard',
    'as' => 'dashboard.'
], function () {
    
    Route::get('/', [DashboardDefaultController::class, 'index'])->name('index');
    
    Route::resource('worker', DashboardWorkerController::class);
    Route::group([
        'prefix' => '/worker',
        'as' => 'worker.'
    ], function () {
        
        // Route::resource('worker', DashboardWorkerController::class);
        Route::post('/data-list', [DashboardWorkerController::class, 'dataList'])->name('data_list');
        Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
            ->name('edit_password')->where('id', '[0-9]+');
        Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
            ->name('update_password')->where('id', '[0-9]+');
        Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
            ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
    
    Route::resource('hall', DashboardHallController::class);
    Route::group([
        'prefix' => '/hall',
        'as' => 'hall.'
    ], function () {
        
        // Route::resource('worker', DashboardWorkerController::class);
        Route::post('/data-list', [DashboardHallController::class, 'dataList'])->name('data_list');
        Route::post('{id}/toggle-closed', [DashboardWorkerController::class, 'toggleClosed'])
            ->name('toggle_closed')->where('id', '[0-9]+');
        // Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
        //     ->name('edit_password')->where('id', '[0-9]+');
        // Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
        //     ->name('update_password')->where('id', '[0-9]+');
        // Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
        //     ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
    
    Route::resource('template', DashboardTemplateController::class);
    Route::group([
        'prefix' => '/template',
        'as' => 'template.'
    ], function () {
        
        // Route::resource('worker', DashboardWorkerController::class);
        Route::post('/data-list', [DashboardTemplateController::class, 'dataList'])->name('data_list');
        // Route::post('{id}/toggle-closed', [DashboardWorkerController::class, 'toggleClosed'])
        //     ->name('toggle_closed')->where('id', '[0-9]+');
        // Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
        //     ->name('edit_password')->where('id', '[0-9]+');
        // Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
        //     ->name('update_password')->where('id', '[0-9]+');
        // Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
        //     ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
    
    Route::resource('client', DashboardClientController::class);
    Route::group([
        'prefix' => '/client',
        'as' => 'client.'
    ], function () {
        
        Route::post('/data-list', [DashboardClientController::class, 'dataList'])->name('data_list');
        Route::get('{id}/edit-password', [DashboardClientController::class, 'editPassword'])
            ->name('edit_password')->where('id', '[0-9]+');
        Route::put('{id}/update-password', [DashboardClientController::class, 'updatePassword'])
            ->name('update_password')->where('id', '[0-9]+');
        // Route::get('/', [DashboardSettingsController::class, 'index'])->name('index');
        
        // Route::resource('worker', DashboardWorkerController::class);
        // Route::post('{id}/toggle-closed', [DashboardWorkerController::class, 'toggleClosed'])
        //     ->name('toggle_closed')->where('id', '[0-9]+');
        // Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
        //     ->name('edit_password')->where('id', '[0-9]+');
        // Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
        //     ->name('update_password')->where('id', '[0-9]+');
        // Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
        //     ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
    
    Route::group([
        'prefix' => '/settings',
        'as' => 'settings.'
    ], function () {
        
        Route::get('/', [DashboardSettingsController::class, 'index'])->name('index');
        
        // Route::resource('worker', DashboardWorkerController::class);
        // Route::post('{id}/toggle-closed', [DashboardWorkerController::class, 'toggleClosed'])
        //     ->name('toggle_closed')->where('id', '[0-9]+');
        // Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
        //     ->name('edit_password')->where('id', '[0-9]+');
        // Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
        //     ->name('update_password')->where('id', '[0-9]+');
        // Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
        //     ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
        
    // Route::get('/', function () {
    //     return view('dashboard');
    //     // Uses first & second middleware...
    // })->name('dashboard');

    // Route::get('/user/profile', function () {
    //     // Uses first & second middleware...
    // });
});

//CalendarBookingController

Route::group([
    'prefix' => '/calendar',
    'as' => 'calendar.'
], function () {
    // dd();
    // Route::get('/booking/owner_{owner_identifier}/((hairdresser_{hairdresser_identifier})?|(hall_{hall_identifier})?)?', function(){
    Route::get('/booking/{owner_id}', [CalendarBookingController::class, 'index'])->where([
        'owner_id' => '[0-9]+',
        // 'identifier' => '(hall_|worker_)[0-9]+',
        // 'hairdresser_identifier' => '[A-Za-z0-9]+',
    ])->name('owner_booking');
    
    // Route::get('/booking/{identifier}', function(){
    //     dd(111111);
    // })->where(['identifier','[A-Za-z0-9]+'])->name('owner_booking');
    // Route::post('/data-list', [DashboardTemplateController::class, 'dataList'])->name('data_list');
});


require __DIR__.'/auth.php';
// require __DIR__.'/auth_worker.php';

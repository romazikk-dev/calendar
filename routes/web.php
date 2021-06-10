<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DefaultController as DashboardDefaultController;
use App\Http\Controllers\Dashboard\WorkerController as DashboardWorkerController;
use App\Http\Controllers\WorkerDashboard\DefaultController as WorkerDashboardDefaultController;
use App\Http\Controllers\Dashboard\HallController as DashboardHallController;
use App\Http\Controllers\Dashboard\TemplateController as DashboardTemplateController;
use App\Http\Controllers\Dashboard\ClientController as DashboardClientController;

use App\Http\Controllers\Dashboard\Ajax\TemplateController as AjaxTemplateController;

use App\Http\Controllers\Dashboard\SettingsController as DashboardSettingsController;

use App\Http\Controllers\Dashboard\Settings\SettingController as DashboardSettingController;
use App\Http\Controllers\Dashboard\Settings\SettingHallController as DashboardSettingHallController;
use App\Http\Controllers\Dashboard\Settings\SettingTemplateController as DashboardSettingTemplateController;
use App\Http\Controllers\Dashboard\Settings\SettingWorkerController as DashboardSettingWorkerController;
use App\Http\Controllers\Dashboard\Settings\SettingClientsBookingCalendarController as DashboardSettingClientsBookingCalendarController;
use App\Http\Controllers\Dashboard\Settings\SettingTemporaryMessageController as DashboardSettingTemporaryMessageController;

use App\Http\Controllers\Dashboard\BookingsController as DashboardBookingsController;

use App\Http\Controllers\Calendar\BookingController as CalendarBookingController;

use App\Http\Controllers\Api\Calendar\Booking\RangeController as ApiRangeController;
use App\Http\Controllers\Api\Calendar\Booking\AuthController as ApiAuthController;
use App\Http\Controllers\Api\Calendar\Booking\WorkerController as ApiWorkerController;
use App\Http\Controllers\Api\Calendar\Booking\TemplateController as ApiTemplateController;
use App\Http\Controllers\Api\Calendar\Booking\BookController as ApiBookController;
use App\Http\Controllers\Api\Calendar\Booking\ClientController as ApiClientController;


use Illuminate\Http\Request;

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
    
    Route::group([
        'prefix' => '/ajax',
        'as' => 'ajax.'
    ], function () {
        
        Route::group([
            'prefix' => '/template',
            'as' => 'template.'
        ], function () {
            
            Route::post('/get', [AjaxTemplateController::class, 'get'])->name('get');
                
            // Route::post('/by_hall/{hall_id}', [AjaxTemplateController::class, 'byHall'])
            //     ->where('id', '[0-9]+')
            //     ->name('by_hall');
            
        });
        
    });
    
    Route::group([
        'prefix' => '/bookings',
        'as' => 'bookings.'
    ], function () {
        
        Route::get('/', [DashboardBookingsController::class, 'index'])->name('index');
        Route::get('/range/{start}/{end}', [DashboardBookingsController::class, 'range'])->where([
            'start' => '\d{2}-\d{2}-\d{4}',
            'end' => '\d{2}-\d{2}-\d{4}',
        ])->name('range');
        
    });
    
    Route::resource('worker', DashboardWorkerController::class);
    Route::group([
        'prefix' => '/worker',
        'as' => 'worker.'
    ], function () {
        
        // Route::resource('worker', DashboardWorkerController::class);
        Route::post('/check-email/{id?}', [DashboardWorkerController::class, 'checkEmail'])
            ->name('check_email')->where('id', '[0-9]+');
        Route::post('/data-list', [DashboardWorkerController::class, 'dataList'])->name('data_list');
        Route::get('{id}/edit-password', [DashboardWorkerController::class, 'editPassword'])
            ->name('edit_password')->where('id', '[0-9]+');
        Route::put('{id}/update-password', [DashboardWorkerController::class, 'updatePassword'])
            ->name('update_password')->where('id', '[0-9]+');
        // Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
        //     ->name('toggle_suspension')->where('id', '[0-9]+');
            
        Route::post('{id}/toggle-suspension', [DashboardWorkerController::class, 'toggleSuspension'])
            ->name('toggle_suspension')->where('id', '[0-9]+');
    
    });
    
    Route::resource('hall', DashboardHallController::class);
    Route::group([
        'prefix' => '/hall',
        'as' => 'hall.'
    ], function () {
        
        Route::post('/data-list', [DashboardHallController::class, 'dataList'])->name('data_list');
        Route::post('{id}/toggle-suspension', [DashboardHallController::class, 'toggleSuspension'])
            ->name('toggle_suspension')->where('id', '[0-9]+');
        Route::post('/check-holidays/{id}', [DashboardHallController::class, 'checkHolidays'])
            ->name('check_holidays');
            // ->where([
            //     'id' => '[0-9]+',
            //     'f' => '\d{2}-\d{2}-\d{4}',
            //     't' => '\d{2}-\d{2}-\d{4}',
            // ]);
    
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
        
        Route::post('/check-email/{id?}', [DashboardClientController::class, 'checkEmail'])
            ->name('check_email')->where('id', '[0-9]+');
        Route::post('/data-list', [DashboardClientController::class, 'dataList'])->name('data_list');
        Route::get('{id}/edit-password', [DashboardClientController::class, 'editPassword'])
            ->name('edit_password')->where('id', '[0-9]+');
        Route::put('{id}/update-password', [DashboardClientController::class, 'updatePassword'])
            ->name('update_password')->where('id', '[0-9]+');
        // Route::get('/', [DashboardSettingsController::class, 'index'])->name('index');
        
        Route::post('{id}/toggle-suspension', [DashboardClientController::class, 'toggleSuspension'])
            ->name('toggle_suspension')->where('id', '[0-9]+');
        
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
        
        Route::get('/', [DashboardSettingController::class, 'index'])->name('index');
        
        Route::group([
            'prefix' => '/temp-msg',
            'as' => 'temp_msg.'
        ], function () {
            
            Route::post('/disable', [DashboardSettingTemporaryMessageController::class, 'disable'])->name('disable');
            
        });
        
        Route::group([
            'prefix' => '/hall',
            'as' => 'hall.'
        ], function () {
            
            Route::get('/', [DashboardSettingHallController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/business_hours', [DashboardSettingHallController::class, 'businessHours'])->name('default_business_hours');
            Route::match(['get', 'post'], '/holidays', [DashboardSettingHallController::class, 'holidays'])->name('holidays');
            Route::match(['get', 'post'], '/timezone', [DashboardSettingHallController::class, 'timezone'])->name('timezone');
            
        });
        
        Route::group([
            'prefix' => '/template',
            'as' => 'template.'
        ], function () {
            
            Route::get('/', [DashboardSettingTemplateController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/specifics', [DashboardSettingTemplateController::class, 'specifics'])->name('specifics');
            // Route::match(['get', 'post'], '/holidays', [DashboardSettingTemplateController::class, 'holidays'])->name('holidays');
            
        });
        
        Route::group([
            'prefix' => '/worker',
            'as' => 'worker.'
        ], function () {
            
            Route::get('/', [DashboardSettingWorkerController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/business_hours', [DashboardSettingWorkerController::class, 'businessHours'])->name('default_business_hours');
            
        });
        
        Route::group([
            'prefix' => '/clients-booking-calendar',
            'as' => 'clients_booking_calendar.'
        ], function () {
            
            Route::get('/', [DashboardSettingClientsBookingCalendarController::class, 'index'])->name('index');
            Route::match(['get', 'post'], '/languages', [DashboardSettingClientsBookingCalendarController::class, 'languages'])->name('languages');
            Route::match(['get', 'post'], '/custom_titles', [DashboardSettingClientsBookingCalendarController::class, 'customTitles'])->name('custom_titles');
            Route::match(['get', 'post'], '/main', [DashboardSettingClientsBookingCalendarController::class, 'main'])->name('main');
            
        });
        
        // Route::match(['get', 'post'], '/business_hours', [DashboardSettingsController::class, 'businessHours'])->name('business_hours');
        
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

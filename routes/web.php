<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DefaultController as DashboardDefaultController;
use App\Http\Controllers\Dashboard\WorkerController as DashboardWorkerController;
use App\Http\Controllers\WorkerDashboard\DefaultController as WorkerDashboardDefaultController;
use App\Http\Controllers\Dashboard\HallController as DashboardHallController;
use App\Http\Controllers\Dashboard\TemplateController as DashboardTemplateController;
use App\Http\Controllers\Dashboard\ClientController as DashboardClientController;
use App\Http\Controllers\Dashboard\SettingsController as DashboardSettingsController;
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

Route::group([
    'prefix' => '/calendar-api',
    'as' => 'calendar_api.'
], function () {
    
    Route::group([
        'prefix' => '/bookings',
        'as' => 'bookings.'
    ], function () {
        
        Route::group([
            'prefix' => '/client',
            'as' => 'client.',
            'middleware' => 'auth:sanctum',
        ], function () {
        
            Route::get('/info', [ApiClientController::class, 'info'])->name('info');
        
        });
        
        Route::group([
            'prefix' => '/book',
            'as' => 'book.'
        ], function () {
        
            Route::post('/create/{user_id}/{hall_id}/{template_id}/{worker_id}', [ApiBookController::class, 'create'])->where([
                'user_id' => '\d+',
                'hall_id' => '\d+',
                'template_id' => '\d+',
                'worker_id' => '\d+',
                // 'start' => '\d{2}-\d{2}-\d{4}',
                // 'end' => '\d{2}-\d{2}-\d{4}',
            ])->name('create');
            
            Route::delete('/cancel/{user_id}/{hall_id}/{template_id}/{worker_id}/{booking_id}', [ApiBookController::class, 'cancel'])->where([
                'user_id' => '\d+',
                'hall_id' => '\d+',
                'template_id' => '\d+',
                'worker_id' => '\d+',
                'booking_id' => '\d+',
                // 'start' => '\d{2}-\d{2}-\d{4}',
                // 'end' => '\d{2}-\d{2}-\d{4}',
            ])->name('cancel');
        
        });
        
        Route::group([
            'prefix' => '/worker',
            'as' => 'worker.'
        ], function () {
        
            Route::get('/', [ApiWorkerController::class, 'index'])->name('index');
        
        });
        
        Route::group([
            'prefix' => '/template',
            'as' => 'template.'
        ], function () {
        
            Route::get('/', [ApiTemplateController::class, 'index'])->name('index');
        
        });
        
        // Route::get('/', [CalendarBookingController::class, 'index'])
        //     ->where([
        //         // 'start' => '\d{2}-\d{2}-\d{4}',
        //         // 'end' => '\d{2}-\d{2}-\d{4}',
        //         'start' => '\d+',
        //         'end' => '\d+',
        //     ]);
    
        // Route::get('/{start}/{end}', function (Request $request) {
        Route::get('/range/{user_id}/{start}/{end}', [ApiRangeController::class, 'index'])->where([
            'user_id' => '\d+',
            'start' => '\d{2}-\d{2}-\d{4}',
            'end' => '\d{2}-\d{2}-\d{4}',
            // 'start' => '\d+',
            // 'end' => '\d+',
        ])->name('range');
        
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('/login/{user_id}', [ApiAuthController::class, 'login'])->where([
            'user_id' => '\d+'
        ])->name('login');
    
    });
    
    Route::get('/tokens/create', function (Request $request) {
        // $token = $request->user()->createToken($request->token_name);
        $token = $request->user()->createToken('my');
        echo  $token->plainTextToken;
    });
    
    Route::get('/tokens/create', function (Request $request) {
        // $token = $request->user()->createToken($request->token_name);
        $token = $request->user()->createToken('my');
        echo  $token->plainTextToken;
    });


    Route::get('/tokens/all', function (Request $request) {
        if ($request->wantsJson()) {
            var_dump(222);
        } else {
            var_dump(111);
        }
        // var_dump($request->user()->tokens);
    });
    // })->middleware('auth:sanctum');

    Route::get('/', function () {
        // return view('welcome');
    });
    
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
    
    Route::group([
        'prefix' => '/bookings',
        'as' => 'bookings.'
    ], function () {
        
        Route::get('/', [DashboardBookingsController::class, 'index'])->name('index');
        
    });
    
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

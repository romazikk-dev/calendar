<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Calendar\Booking\RangeController as ApiRangeController;
use App\Http\Controllers\Api\Calendar\Booking\AuthController as ApiAuthController;
use App\Http\Controllers\Api\Calendar\Booking\WorkerController as ApiWorkerController;
use App\Http\Controllers\Api\Calendar\Booking\TemplateController as ApiTemplateController;
use App\Http\Controllers\Api\Calendar\Booking\BookController as ApiBookController;
use App\Http\Controllers\Api\Calendar\Booking\ClientController as ApiClientController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     // echo 1;
//     // die();
//     return $request->user();
// });


Route::group([
    'prefix' => '/calendar',
    'as' => 'api.calendar.'
], function () {
    
    // Route::group([
    //     // 'prefix' => '/bookings',
    //     'prefix' => '/test/{user:id}',
    //     'as' => 'test.',
    //     'where' => [
    //         'user' => '\d+'
    //     ],
    //     'missing' => function (Request $request) {
    //         // return Redirect::route('locations.index');
    //         abort(404, 'Page not found');
    //     }
    // ], function () {
    // 
    //     Route::get('/test', [ApiBookController::class, 'test'])->where([
    //         // 'user_id' => '\d+',
    //     ])->name('test');
    // 
    // });
    
    Route::group([
        'prefix' => '/bookings/{user:id}',
        // 'prefix' => '/bookings/{id}',
        'as' => 'bookings.',
        'where' => [
            'user' => '\d+'
        ],
        'missing' => function (Request $request) {
            abort(404, 'Page not found');
        }
    ], function () {
        
        // dd($id);
        
        Route::group([
            'prefix' => '/client',
            'as' => 'client.',
            'middleware' => 'auth:sanctum',
            // 'middleware' => 'sanctum:client',
        ], function () {
        
            Route::get('/info', [ApiClientController::class, 'info'])->name('info');
        
        });
        
        Route::group([
            'prefix' => '/book',
            'as' => 'book.',
            'middleware' => 'auth:sanctum',
        ], function () {
            
            Route::get('/all/{from_date?}/{to_date?}', [ApiBookController::class, 'all'])->where([
                'from_date' => '\d{4}-\d{2}-\d{2}_\d{2}:\d{2}:\d{2}',
                'to_date' => '\d{4}-\d{2}-\d{2}_\d{2}:\d{2}:\d{2}'
            ])->name('all');
            
            Route::post('/create/{hall_id}/{template_id}/{worker_id}', [ApiBookController::class, 'create'])->where([
                'hall_id' => '\d+',
                'template_id' => '\d+',
                'worker_id' => '\d+',
            ])->name('create');
            
            Route::delete('/cancel/{hall_id}/{template_id}/{worker_id}/{booking_id}', [ApiBookController::class, 'cancel'])->where([
                'hall_id' => '\d+',
                'template_id' => '\d+',
                'worker_id' => '\d+',
                'booking_id' => '\d+',
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
        
        Route::group([
            'prefix' => '/range',
            'as' => 'range.'
        ], function () {
        
            Route::get('/guest/{start}/{end}', [ApiRangeController::class, 'guest'])->where([
                'start' => '\d{2}-\d{2}-\d{4}',
                'end' => '\d{2}-\d{2}-\d{4}',
            ])->name('guest');
            
            Route::get('/client/{start}/{end}', [ApiRangeController::class, 'client'])->where([
                'start' => '\d{2}-\d{2}-\d{4}',
                'end' => '\d{2}-\d{2}-\d{4}',
            ])->middleware('auth:sanctum')->name('client');
            
            // 'middleware' => 'auth:sanctum',
        
        });
    
        // Route::get('/range/{start}/{end}', [ApiRangeController::class, 'index'])->where([
        //     'start' => '\d{2}-\d{2}-\d{4}',
        //     'end' => '\d{2}-\d{2}-\d{4}',
        // ])->name('range');
        
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
    
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

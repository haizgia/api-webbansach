<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\BillController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login'])->name('api.login');
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/authentication', function ()
{
    $res = [
        "success"=>false,
        "mesage"=>"you are must login",
    ];
    return response()->json($res, 400);
})->name('authentication');

Route::middleware(['auth:api','role:admin'])->prefix('admin')->group(function () {
    Route::prefix('books')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('publishers')->group(function () {
        Route::get('/', [PublisherController::class, 'index']);
        Route::get('/{id}', [PublisherController::class, 'show']);
        Route::post('/', [PublisherController::class, 'store']);
        Route::put('/{id}', [PublisherController::class, 'update']);
        Route::delete('/{id}', [PublisherController::class, 'destroy']);
    });

    Route::prefix('bills')->group(function () {
        Route::get('/', [BillController::class, 'index']);
        Route::get('/{id}', [BillController::class, 'show']);
        // Route::post('/', [BillController::class, 'store']);
        // Route::put('/{id}', [BillController::class, 'update']);
        Route::put('/up-status/{id}', [BillController::class, 'updateStatus']);
        // Route::delete('/{id}', [BillController::class, 'destroy']);
    });

});

Route::prefix('books')->group(function () {
    Route::get('/new', [ProductController::class, 'getNew']);
    Route::get('/cheapest', [ProductController::class, 'getCheapest']);
    Route::get('/detail/{id}', [ProductController::class, 'show']);
    Route::get('/category/{id}', [ProductController::class, 'getByCategory']);
    Route::get('/search', [ProductController::class, 'search']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

Route::middleware(['auth:api','role:user'])->prefix('shopping')->group(function () {
    Route::post('/', [BillController::class, 'store']);
    Route::get('/list', [BillController::class, 'getListByUser']);
    Route::get('/{id}', [BillController::class, 'show']);
    Route::get('/cancel/{id}', [BillController::class, 'cancel']);
});

<?php

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\WorkspaceController;
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
Route::group([
    'middleware' => ['auth:sanctum'],
], function () {

    // Workspaces API resource & routes
    Route::group([
        'middleware' => 'workspace.board'
    ], function () {
        Route::prefix('/workspaces/{workspace}')->group(function () {
            Route::apiResource('boards', BoardController::class);
            Route::prefix('/boards/{board}')->group(function () {

                Route::post('/save', [BoardController::class, 'saveBoardChanges']);
                Route::post('/columns', [BoardController::class, 'storeBoardColumn']);
                Route::patch('/columns/{boardColumn}', [BoardController::class, 'updateBoardColumn']);
                Route::delete('/columns/{boardColumn}', [BoardController::class, 'destroyBoardColumn']);

                Route::prefix('/columns/{boardColumn}')->group(function () {
                    Route::post('/tasks', [BoardController::class, 'storeTask']);
                    Route::patch('/tasks/{task}', [BoardController::class, 'updateTask']);
                    Route::delete('/tasks/{task}', [BoardController::class, 'destroyTask']);

                    Route::prefix('/tasks/{task}')->group(function () {
                        Route::post('/comments', [BoardController::class, 'storeComment']);
                        Route::patch('/comments/{comment}', [BoardController::class, 'updateComment']);
                        Route::delete('/comments/{comment}', [BoardController::class, 'destroyComment']);
                    });
                });
            });
        });
    });

    Route::group([
        'middleware' => 'workspace.user'
    ], function () {
        Route::apiResource('workspaces', WorkspaceController::class);
    });

    // Auth check
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

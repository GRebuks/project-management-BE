<?php

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
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

    // Search routes
    Route::get('/users/search', [UserController::class, 'searchAllExceptLoggedIn']);
    Route::get('/workspaces/{workspace}/nonparticipants', [UserController::class, 'searchAllExceptLoggedInAndWorkspaceParticipants']);
    Route::get('/workspaces/{workspace}/participants', [UserController::class, 'searchWorkspaceParticipants']);
    Route::get('/workspaces/{workspace}/search-excluding-logged-in', [UserController::class, 'searchWorkspaceParticipantsExcludingLoggedIn']);

    // Workspaces API resource & routes
    Route::group([
        'middleware' => 'workspace.board'
    ], function () {

        // Friend request routes
        Route::post('/friend-request/{user}', [UserController::class, 'sendFriendRequest']);
        Route::post('/friend-request/{user}/accept', [UserController::class, 'acceptFriendRequest']);
        Route::post('/friend-request/{user}/reject', [UserController::class, 'rejectFriendRequest']);
        Route::post('/friend-request/{user}/break', [UserController::class, 'breakFriendRequest']);

        Route::prefix('/workspaces/{workspace}')->group(function () {
            Route::post('/participants/add', [WorkspaceController::class, 'addParticipant']);
            Route::post('/participants/remove', [WorkspaceController::class, 'removeParticipant']);
            Route::apiResource('boards', BoardController::class);
            Route::prefix('/boards/{board}')->group(function () {

                Route::post('/save', [BoardController::class, 'saveBoardChanges']);
                Route::post('/reorder', [BoardController::class, 'reorderTask']);

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
        Route::prefix('/tasks/{task}')->group(function () {
            Route::post('/participants/add', [TaskController::class, 'addParticipant']);
            Route::post('/participants/remove', [TaskController::class, 'removeParticipant']);
        });
        Route::prefix('/user')->group(function () {
            Route::get('/preferences', [UserController::class, 'getPreferences']);
            Route::post('/preferences', [UserController::class, 'setPreferences']);
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

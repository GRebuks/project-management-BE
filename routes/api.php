<?php

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ProjectController;
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

    // Checks if user is authorized to access the specified project.
    // Only checks if the URI contains '{project}', otherwise the user is authorized to access the resource.
    Route::middleware(['project.owner'])->group(function () {

        // Projects API resource & routes
        Route::apiResource('projects', ProjectController::class);
        Route::get('/users/{user_id}/projects', [ProjectController::class, 'getUserProjects']);

        // Boards API resource & routes
        Route::middleware(['project.board'])->group(function () {
            Route::prefix('/projects/{project}')->group(function () {
                Route::apiResource('boards', BoardController::class);
            });
        });
    });

    // Auth check
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AutomatorTaskController;

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


Route::middleware('scope.user')->group(function () {
    Route::get('/unassignedtasks', [AutomatorTaskController::class, 'getHeadOfUnitAssignableTask'])->name('unassignedtasks.getHeadOfUnitAssignableTask');
    Route::get('/task-assignable-users/{id}', [AutomatorTaskController::class, 'getTaskAssignableUsers'])->name('task-assignable-users.getTaskAssignableUsers');
    Route::post('/assign-task-to-user', [AutomatorTaskController::class, 'assignTaskToHeadOfUnitSubordinate'])->name('assign-task-to-user.assignTaskToHeadOfUnitSubordinate');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Complexity\ComplexityController;
use App\Http\Controllers\ExcelImport\ExcelImportController;
use App\Http\Controllers\Interaction\InteractionController;
use App\Http\Controllers\InteractionFile\InteractionFileController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Plan\PlanController;
use App\Http\Controllers\Priority\PriorityController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Statistic\StatisticController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\TaskFile\TaskFileController;
use App\Http\Controllers\TaskStatus\TaskStatusController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::post('login', LoginController::class);
Route::post('logout', LogoutController::class);
Route::post('register', RegisterController::class);
Route::post('verify-email', VerifyEmailController::class);
Route::post('forgot-password', ForgotPasswordController::class);
Route::post('reset-password', ResetPasswordController::class);
Route::get('tasks/statistics', [TaskController::class, 'getTaskStatistics']);
Route::post('import', [ExcelImportController::class, 'import']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [MeController::class, 'show']);




    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('update-first-name', [UserController::class, 'updateFirstName']);

    Route::get('teams', [TeamController::class, 'index']);
    Route::post('teams', [TeamController::class, 'store']);
    Route::put('teams/{team:token}', [TeamController::class, 'update']);
    Route::delete('teams/{team:token}', [TeamController::class, 'destroy']);

    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::put('tasks/{id}', [TaskController::class, 'update']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('tasks/statistics', [TaskController::class, 'getTaskStatistics']);
    Route::get('tasks/statistics', [TaskController::class, 'getTaskStatistics']);

    Route::get('priorities', [PriorityController::class, 'index']);
    Route::post('priorities', [PriorityController::class, 'store']);
    Route::put('priorities/{priority}', [PriorityController::class, 'update']);
    Route::get('priorities/{id}', [PriorityController::class, 'show']);
    Route::delete('priorities/{priority}', [PriorityController::class, 'destroy']);

    Route::get('complexities', [ComplexityController::class, 'index']);
    Route::post('complexities', [ComplexityController::class, 'store']);
    Route::put('complexities/{complexity}', [ComplexityController::class, 'update']);
    Route::get('complexities/{id}', [ComplexityController::class, 'show']);
    Route::delete('complexities/{complexity}', [ComplexityController::class, 'destroy']);

    Route::get('interactions', [InteractionController::class, 'index']);
    Route::post('interactions', [InteractionController::class, 'store']);
    Route::put('interactions/{interaction}', [InteractionController::class, 'update']);
    Route::get('interactions/{id}', [InteractionController::class, 'show']);
    Route::delete('interactions/{interaction}', [InteractionController::class, 'destroy']);

    Route::get('interaction-file', [InteractionFileController::class, 'index']);
    Route::post('interaction-file', [InteractionFileController::class, 'store']);
    Route::put('interaction-file/{interactionFile}', [InteractionFileController::class, 'update']);
    Route::get('interaction-file/{id}', [InteractionFileController::class, 'show']);
    Route::delete('interaction-file/{interactionFile}', [InteractionFileController::class, 'destroy']);

    Route::get('task-file', [TaskFileController::class, 'index']);
    Route::post('task-file', [TaskFileController::class, 'store']);
    Route::put('task-file/{taskFile}', [TaskFileController::class, 'update']);
    Route::get('task-file/{id}', [TaskFileController::class, 'show']);
    Route::delete('task-file/{interactionFile}', [TaskFileController::class, 'destroy']);

    Route::get('tasks-status', [TaskStatusController::class, 'index']);
    Route::post('tasks-status', [TaskStatusController::class, 'store']);
    Route::put('tasks-status/{taskStatus}', [TaskStatusController::class, 'update']);
    Route::get('tasks-status/{taskId}', [TaskStatusController::class, 'show']);
    Route::delete('tasks-status/{taskStatus}', [TaskStatusController::class, 'destroy']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{users}', [UserController::class, 'update']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{users}', [UserController::class, 'destroy']);
    Route::get('users/notifications/{id}', [UserController::class, 'markNotificationComplete']);
    Route::get('users/notifications', [UserController::class, 'markAllNotificationComplete']);

    // Rotas de broadcasting
    // Broadcast::routes(['middleware' => ['auth:sanctum']]);

    // Rotas que precisam de team
    Route::middleware(['team'])->group(function () {
        // Adicione as rotas específicas para teams aqui
    });
});

Route::middleware(['auth:sanctum', 'team'])->get('test', function() {
    return 'ok';
});
Route::get('/phpinfo', function() {
    phpinfo();
});

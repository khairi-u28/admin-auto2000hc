<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\CurriculumController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\ProgressController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\AnalyticsController;

/*
|--------------------------------------------------------------------------
| API Routes — Ruang Kompetensi v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Public
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',     [AuthController::class, 'me']);

        // Employees
        Route::get('employees',          [EmployeeController::class, 'index']);
        Route::get('employees/{employee}', [EmployeeController::class, 'show']);
        Route::put('employees/{employee}', [EmployeeController::class, 'update']);

        // Curricula
        Route::get('curricula',              [CurriculumController::class, 'index']);
        Route::get('curricula/{curriculum}', [CurriculumController::class, 'show']);

        // Courses
        Route::get('courses',         [CourseController::class, 'index']);
        Route::get('courses/{course}', [CourseController::class, 'show']);

        // Progress (enrollments)
        Route::get('progress',                     [ProgressController::class, 'index']);
        Route::patch('progress/{enrollment}/update', [ProgressController::class, 'update']);

        // Quiz
        Route::get('quiz/{enrollment}',        [QuizController::class, 'show']);
        Route::post('quiz/{enrollment}/submit', [QuizController::class, 'submit']);

        // Analytics
        Route::get('analytics/overview',                 [AnalyticsController::class, 'overview']);
        Route::get('analytics/completion-by-department', [AnalyticsController::class, 'completionByDepartment']);
        Route::get('analytics/competency-gap',           [AnalyticsController::class, 'competencyGap']);
    });
});

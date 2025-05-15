<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditTemplateController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::apiResource('audit-templates', AuditTemplateController::class);
    Route::post('audit-templates/{auditTemplate}/questions', [AuditTemplateController::class, 'addQuestion']);
    Route::put('audit-templates/{auditTemplate}/questions/{question}', [AuditTemplateController::class, 'updateQuestion']);
    Route::delete('audit-templates/{auditTemplate}/questions/{question}', [AuditTemplateController::class, 'deleteQuestion']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('audits', AuditController::class);
    Route::post('audits/{audit}/assign-auditors', [AuditController::class, 'assignAuditors']);
    Route::post('audits/{audit}/responses', [AuditController::class, 'recordResponse']);
    Route::apiResource('action-plans', ActionPlanController::class);
});
Route::middleware(['auth:sanctum', 'role:Admin'])->get('dashboard/admin', [DashboardController::class, 'admin']);
Route::middleware(['auth:sanctum', 'role:Manager'])->get('dashboard/manager', [DashboardController::class, 'manager']);
Route::middleware(['auth:sanctum', 'role:Staff'])->get('dashboard/staff', [DashboardController::class, 'staff']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('search/audits', [SearchController::class, 'audits']);
    Route::get('search/action-plans', [SearchController::class, 'actionPlans']);
    Route::get('search/audit-templates', [SearchController::class, 'auditTemplates']);
}); 
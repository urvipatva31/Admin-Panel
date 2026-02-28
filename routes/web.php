<?php

use App\Http\Controllers\MembersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HrManagementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;

use App\Http\Controllers\AuditLogController;

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('register', 'register')->name('register');
Route::post('register', [MembersController::class, 'register'])->name('register.post');

Route::view('login', 'login')->name('login');
Route::post('login', [MembersController::class, 'login'])->name('login.post');

Route::get('logout', [MembersController::class, 'logout'])->name('logout');

Route::get('dashboard',[DashboardController::class , 'index'])->name('dashboard');

Route::get('user-management', [UserController::class, 'index'])->name('users');
Route::post('user-management/store', [UserController::class, 'store'])->name('users.store');
Route::get('user-management/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::put('user-management/update/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('user-management/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

Route::get('role-permission', [RolePermissionController::class, 'index'])->name('roles');
Route::post('role-permission', [RolePermissionController::class, 'store'])->name('roles.store');

Route::get('projects', [ProjectController::class, 'index'])->name('projects');
Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
Route::post('projects/update/{id}', [ProjectController::class, 'update'])->name('projects.update');
Route::get('projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('hr-management', [HrManagementController::class, 'index'])->name('hr-management');
Route::post('hr-management/store', [HrManagementController::class, 'store'])->name('hr-management.store');
Route::get('hr-management/edit/{id}', [HrManagementController::class, 'edit'])->name('hr-management.edit');
Route::put('hr-management/update/{id}', [HrManagementController::class, 'update'])->name('hr-management.update');
Route::get('hr-management/delete/{id}', [HrManagementController::class, 'destroy'])->name('hr-management.delete');

Route::get('tasks', [TaskController::class, 'index'])->name('tasks');
Route::post('tasks/store', [TaskController::class, 'store'])->name('tasks.store');
Route::get('tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::post('tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');

Route::get('reports', [ReportController::class, 'index'])->name('reports');
Route::get('reports/view/{id}', [ReportController::class, 'view'])->name('reports.view');
Route::get('reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::post('leave/apply', [LeaveController::class, 'apply']);
Route::get('leave', [LeaveController::class, 'index'])->name('leave');
Route::post('leave/approve/{id}', [LeaveController::class, 'approve']);
Route::post('leave/reject/{id}', [LeaveController::class, 'reject']);

Route::get('payroll', [PayrollController::class, 'index'])->name('payroll');
Route::post('payroll/store', [PayrollController::class, 'store'])->name('payroll.store');

Route::view('system-settings', 'pages.system-settings')->name('system-settings');

Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit');


Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
Route::get('profile/photo/remove', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

Route::view('settings','pages.settings');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApiClientController;
use App\Http\Controllers\Admin\BitcoinMonitorController;
use App\Http\Controllers\Admin\CommandExecutionController;

// Public Routes
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Authentication
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Dashboard (Protected)
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Bitcoin Daemon Monitoring
Route::get('/admin/bitcoin/monitor', [BitcoinMonitorController::class, 'index'])->name('admin.bitcoin.monitor');
Route::post('/admin/bitcoin/monitor/refresh', [BitcoinMonitorController::class, 'refresh'])->name('admin.bitcoin.refresh');

// Command Execution
Route::get('/admin/bitcoin/commands', [CommandExecutionController::class, 'index'])->name('admin.bitcoin.commands');
Route::post('/admin/bitcoin/commands/execute', [CommandExecutionController::class, 'execute'])->name('admin.bitcoin.execute');

// API Client Management
Route::get('/admin/clients', [ApiClientController::class, 'index'])->name('admin.clients.index');
Route::get('/admin/clients/create', [ApiClientController::class, 'create'])->name('admin.clients.create');
Route::post('/admin/clients', [ApiClientController::class, 'store'])->name('admin.clients.store');
Route::get('/admin/clients/{id}/edit', [ApiClientController::class, 'edit'])->name('admin.clients.edit');
Route::put('/admin/clients/{id}', [ApiClientController::class, 'update'])->name('admin.clients.update');
Route::delete('/admin/clients/{id}', [ApiClientController::class, 'destroy'])->name('admin.clients.destroy');
Route::post('/admin/clients/{id}/regenerate-keys', [ApiClientController::class, 'regenerateKeys'])->name('admin.clients.regenerate');
Route::post('/admin/clients/{id}/toggle-status', [ApiClientController::class, 'toggleStatus'])->name('admin.clients.toggle');
Route::get('/admin/clients/{id}/permissions', [ApiClientController::class, 'permissions'])->name('admin.clients.permissions');
Route::put('/admin/clients/{id}/permissions', [ApiClientController::class, 'updatePermissions'])->name('admin.clients.permissions.update');
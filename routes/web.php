<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\AdminUserController as AdminUserController;
use App\Http\Controllers\Admin\RackLocationController as AdminRackLocationController;
use App\Http\Controllers\Admin\SettlementController as AdminSettlementController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\ShipmentController as ClientShipmentController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Agent\AuthController as AgentAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ScanController as AgentScanController;

Route::get('/', function () {
    return view('welcome-backup');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Client Management
        Route::resource('clients', AdminClientController::class)->names([
            'index' => 'admin.clients.index',
            'create' => 'admin.clients.create',
            'store' => 'admin.clients.store',
            'show' => 'admin.clients.show',
        ]);
        
        // Agent Management
        Route::resource('agents', AdminAgentController::class)->names([
            'index' => 'admin.agents.index',
            'create' => 'admin.agents.create',
            'store' => 'admin.agents.store',
        ]);
        
        // Admin User Management
        Route::resource('admins', AdminUserController::class)->names([
            'index' => 'admin.admins.index',
            'create' => 'admin.admins.create',
            'store' => 'admin.admins.store',
        ]);
        
        // Rack Location Management
        Route::resource('rack-locations', AdminRackLocationController::class)->names([
            'index' => 'admin.rack-locations.index',
            'create' => 'admin.rack-locations.create',
            'store' => 'admin.rack-locations.store',
        ]);
        
        // Settlement Management
        Route::get('/settlements', [AdminSettlementController::class, 'index'])->name('admin.settlements.index');
        Route::post('/settlements/{settlement}/approve', [AdminSettlementController::class, 'approve'])->name('admin.settlements.approve');
        Route::post('/settlements/{settlement}/paid', [AdminSettlementController::class, 'markAsPaid'])->name('admin.settlements.paid');
    });
});

// Client Routes
Route::prefix('client')->group(function () {
    Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
    
    Route::middleware('auth:client')->group(function () {
        Route::get('/dashboard', function () {
            return view('client.dashboard');
        })->name('client.dashboard');
        
        // Shipment Management
        Route::resource('shipments', ClientShipmentController::class)->names([
            'index' => 'client.shipments.index',
            'create' => 'client.shipments.create',
            'store' => 'client.shipments.store',
            'show' => 'client.shipments.show',
        ]);
        
        // Order Management
        Route::resource('orders', ClientOrderController::class)->names([
            'index' => 'client.orders.index',
            'create' => 'client.orders.create',
            'store' => 'client.orders.store',
            'show' => 'client.orders.show',
        ]);
    });
});

// Agent Routes
Route::prefix('agent')->group(function () {
    Route::get('/login', [AgentAuthController::class, 'showLoginForm'])->name('agent.login');
    Route::post('/login', [AgentAuthController::class, 'login']);
    Route::post('/logout', [AgentAuthController::class, 'logout'])->name('agent.logout');
    
    Route::middleware('auth:agent')->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
        
        // Shipment Details
        Route::get('/shipments/{id}', [AgentScanController::class, 'showShipment'])->name('agent.shipments.show');
        
        // Scan Operations
        Route::get('/scan/shipment', [AgentScanController::class, 'scanShipment'])->name('agent.scan.shipment');
        Route::post('/scan/shipment', [AgentScanController::class, 'processScan1']);
        
        Route::get('/scan/order-prep', [AgentScanController::class, 'scanOrderPrep'])->name('agent.scan.order-prep');
        Route::post('/scan/order-prep', [AgentScanController::class, 'processScan2']);
        
        Route::get('/scan/order-handover', [AgentScanController::class, 'scanOrderHandover'])->name('agent.scan.order-handover');
        Route::post('/scan/order-handover', [AgentScanController::class, 'processScan3']);
    });
});

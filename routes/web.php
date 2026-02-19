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
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\Agent\AuthController as AgentAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ScanController as AgentScanController;

Route::get('/', function () {
    return view('welcome-backup');
});
Route::post('/login', [UnifiedLoginController::class, 'login'])->name('login');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Separate Table Pages
        Route::get('/all-shipments', [AdminDashboardController::class, 'allShipments'])->name('admin.all-shipments');
        Route::get('/no-rack-shipments', [AdminDashboardController::class, 'noRackShipments'])->name('admin.no-rack-shipments');
        Route::get('/unknown-shipments', [AdminDashboardController::class, 'unknownShipments'])->name('admin.unknown-shipments');
        
        // Unknown Shipment Creation - MUST come before {id} wildcard
        Route::get('/shipments/create-unknown', [AdminDashboardController::class, 'createUnknownShipment'])->name('admin.shipments.create-unknown');
        Route::post('/shipments/store-unknown', [AdminDashboardController::class, 'storeUnknownShipment'])->name('admin.shipments.store-unknown');
        
        // Unknown Shipment Editing - MUST come before {id} wildcard
        Route::get('/shipments/{id}/edit-unknown', [AdminDashboardController::class, 'editUnknownShipment'])->name('admin.shipments.edit-unknown');
        Route::put('/shipments/{id}/update-unknown', [AdminDashboardController::class, 'updateUnknownShipment'])->name('admin.shipments.update-unknown');
        
        // Rack Assignment
        Route::patch('/shipments/{id}/assign-rack', [AdminDashboardController::class, 'assignRack'])->name('admin.shipments.assign-rack');
        
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
            'edit' => 'client.shipments.edit',
            'update' => 'client.shipments.update',
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
        
        // Separate Table Pages
        Route::get('/pending-shipments', [AgentDashboardController::class, 'pendingShipments'])->name('agent.pending-shipments');
        Route::get('/no-rack-shipments', [AgentDashboardController::class, 'noRackShipments'])->name('agent.no-rack-shipments');
        Route::get('/unknown-shipments', [AgentDashboardController::class, 'unknownShipments'])->name('agent.unknown-shipments');
        
        // Unknown Shipment Creation - MUST come before {id} wildcard
        Route::get('/shipments/create-unknown', [AgentDashboardController::class, 'createUnknownShipment'])->name('agent.shipments.create-unknown');
        Route::post('/shipments/store-unknown', [AgentDashboardController::class, 'storeUnknownShipment'])->name('agent.shipments.store-unknown');
        
        // Unknown Shipment Editing - MUST come before {id} wildcard
        Route::get('/shipments/{id}/edit-unknown', [AgentDashboardController::class, 'editUnknownShipment'])->name('agent.shipments.edit-unknown');
        Route::put('/shipments/{id}/update-unknown', [AgentDashboardController::class, 'updateUnknownShipment'])->name('agent.shipments.update-unknown');
        
        // Shipment Details - Wildcard route comes AFTER specific routes
        Route::get('/shipments/{id}', [AgentScanController::class, 'showShipment'])->name('agent.shipments.show');
        
        // Rack Assignment
        Route::patch('/shipments/{id}/assign-rack', [AgentDashboardController::class, 'assignRack'])->name('agent.shipments.assign-rack');
        
        // Scan Operations
        Route::get('/scan/shipment', [AgentScanController::class, 'scanShipment'])->name('agent.scan.shipment');
        Route::post('/scan/shipment', [AgentScanController::class, 'processScan1']);
        
        Route::get('/scan/order-prep', [AgentScanController::class, 'scanOrderPrep'])->name('agent.scan.order-prep');
        Route::post('/scan/order-prep', [AgentScanController::class, 'processScan2']);
        
        Route::get('/scan/order-handover', [AgentScanController::class, 'scanOrderHandover'])->name('agent.scan.order-handover');
        Route::post('/scan/order-handover', [AgentScanController::class, 'processScan3']);
    });
});

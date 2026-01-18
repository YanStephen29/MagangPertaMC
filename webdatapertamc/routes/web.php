<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\RequestItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Event Management Routes
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    Route::get('/{event}/export', [EventController::class, 'exportSingle'])->name('export.single');
});

// Export All Events
Route::get('/export-all', [EventController::class, 'exportAll'])->name('events.export.all');

// Tool Management Routes (Legacy - will be replaced by Request Items)
Route::prefix('events/{event}/tools')->name('tools.')->group(function () {
    Route::get('/create', [ToolController::class, 'create'])->name('create');
    Route::post('/', [ToolController::class, 'store'])->name('store');
    Route::get('/{tool}/edit', [ToolController::class, 'edit'])->name('edit');
    Route::put('/{tool}', [ToolController::class, 'update'])->name('update');
    Route::delete('/{tool}', [ToolController::class, 'destroy'])->name('destroy');
});

// Request Item Management Routes (New System)
Route::prefix('request-items')->name('request-items.')->group(function () {
    Route::get('/', [RequestItemController::class, 'index'])->name('index');
    Route::get('/create', [RequestItemController::class, 'create'])->name('create');
    Route::post('/', [RequestItemController::class, 'store'])->name('store');
    Route::get('/{requestItem}', [RequestItemController::class, 'show'])->name('show');
    Route::get('/{requestItem}/edit', [RequestItemController::class, 'edit'])->name('edit');
    Route::put('/{requestItem}', [RequestItemController::class, 'update'])->name('update');
    Route::delete('/{requestItem}', [RequestItemController::class, 'destroy'])->name('destroy');
    Route::patch('/{requestItem}/workflow', [RequestItemController::class, 'updateWorkflowStage'])->name('workflow.update');
});

// API Routes for Request Items
Route::prefix('api/request-items')->name('api.request-items.')->group(function () {
    Route::get('/by-event/{eventNoIo}', [RequestItemController::class, 'byEvent'])->name('by-event');
});

// Bidang Management Routes
Route::prefix('bidangs')->name('bidangs.')->group(function () {
    Route::get('/', [BidangController::class, 'index'])->name('index');
    Route::get('/create', [BidangController::class, 'create'])->name('create');
    Route::post('/', [BidangController::class, 'store'])->name('store');
    Route::get('/{bidang}', [BidangController::class, 'show'])->name('show');
    Route::get('/{bidang}/edit', [BidangController::class, 'edit'])->name('edit');
    Route::put('/{bidang}', [BidangController::class, 'update'])->name('update');
    Route::delete('/{bidang}', [BidangController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

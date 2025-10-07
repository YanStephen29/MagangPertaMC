<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\BoqController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Splash screen and authentication routes
Route::get('/', function () {
    return view('splash');
})->name('splash');

// Admin Authentication routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Management routes - using new CRUD privilege system
    Route::prefix('admin/management')->name('admin.management.')->group(function () {
        Route::get('/', [AdminManagementController::class, 'index'])->name('index')->middleware('admin:account_view');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create')->middleware('admin:account_add');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store')->middleware('admin:account_add');
        Route::get('/{admin}', [AdminManagementController::class, 'show'])->name('show')->middleware('admin:account_view');
        Route::get('/{admin}/edit', [AdminManagementController::class, 'edit'])->name('edit')->middleware('admin:account_edit');
        Route::put('/{admin}', [AdminManagementController::class, 'update'])->name('update')->middleware('admin:account_edit');
        Route::delete('/{admin}', [AdminManagementController::class, 'destroy'])->name('destroy')->middleware('admin:account_delete');
    });
    
    // Redirect home and old routes to projects index
    Route::get('/home', function () {
        return redirect()->route('projects.index');
    })->name('home');
});

// Protected application routes with CRUD privilege checking
Route::middleware(['admin'])->group(function () {
    // Project routes with CRUD privilege checking
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index')->middleware('admin:project_view');
        Route::get('/create', [ProjectController::class, 'create'])->name('create')->middleware('admin:project_add');
        Route::post('/', [ProjectController::class, 'store'])->name('store')->middleware('admin:project_add');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show')->middleware('admin:project_view');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit')->middleware('admin:project_edit');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('update')->middleware('admin:project_edit');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy')->middleware('admin:project_delete');
        
        // Assignment routes - project assignment privilege
        Route::get('/{project}/assign', [ProjectController::class, 'showAssignForm'])->name('assign.show')->middleware('admin:project_assign');
        Route::post('/{project}/assign', [ProjectController::class, 'assignPM'])->name('assign.store')->middleware('admin:project_assign');
    });
    
    // Bidang routes with CRUD privilege checking
    Route::prefix('bidangs')->name('bidangs.')->group(function () {
        Route::get('/', [BidangController::class, 'index'])->name('index')->middleware('admin:kode_bidang_view');
        Route::get('/create', [BidangController::class, 'create'])->name('create')->middleware('admin:kode_bidang_add');
        Route::post('/', [BidangController::class, 'store'])->name('store')->middleware('admin:kode_bidang_add');
        Route::get('/{bidang}', [BidangController::class, 'show'])->name('show')->middleware('admin:kode_bidang_view');
        Route::get('/{bidang}/edit', [BidangController::class, 'edit'])->name('edit')->middleware('admin:kode_bidang_edit');
        Route::patch('/{bidang}', [BidangController::class, 'update'])->name('update')->middleware('admin:kode_bidang_edit');
        Route::delete('/{bidang}', [BidangController::class, 'destroy'])->name('destroy')->middleware('admin:kode_bidang_delete');
    });
    
    // Document routes with CRUD privilege checking
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index')->middleware('admin:document_view');
        Route::get('/create', [DocumentController::class, 'create'])->name('create')->middleware('admin:document_add');
        Route::post('/', [DocumentController::class, 'store'])->name('store')->middleware('admin:document_add');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show')->middleware('admin:document_view');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit')->middleware('admin:document_edit');
        Route::patch('/{document}', [DocumentController::class, 'update'])->name('update')->middleware('admin:document_edit');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy')->middleware('admin:document_delete');
    });

    // Tool routes with CRUD privilege checking
    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::get('tools', [ToolController::class, 'index'])->name('tools.index')->middleware('admin:tools_view');
        Route::get('tools/create', [ToolController::class, 'create'])->name('tools.create')->middleware('admin:tools_add');
        Route::post('tools', [ToolController::class, 'store'])->name('tools.store')->middleware('admin:tools_add');
        Route::get('tools/{tool}', [ToolController::class, 'show'])->name('tools.show')->middleware('admin:tools_view');
        Route::get('tools/{tool}/edit', [ToolController::class, 'edit'])->name('tools.edit')->middleware('admin:tools_edit');
        Route::patch('tools/{tool}', [ToolController::class, 'update'])->name('tools.update')->middleware('admin:tools_edit');
        Route::delete('tools/{tool}', [ToolController::class, 'destroy'])->name('tools.destroy')->middleware('admin:tools_delete');
        Route::patch('tools/{tool}/quick-assign', [ToolController::class, 'quickAssignDocument'])->name('tools.quick-assign')->middleware('admin:tools_edit');
        
        // BOQ routes - only for Admin/PM (middleware handled in controller)
        Route::get('boq', [\App\Http\Controllers\BoqController::class, 'index'])->name('boq.index');
        Route::get('boq/create', [\App\Http\Controllers\BoqController::class, 'create'])->name('boq.create');
        Route::post('boq', [\App\Http\Controllers\BoqController::class, 'store'])->name('boq.store');
        Route::get('boq/{boq}', [\App\Http\Controllers\BoqController::class, 'show'])->name('boq.show');
        Route::get('boq/{boq}/edit', [\App\Http\Controllers\BoqController::class, 'edit'])->name('boq.edit');
        Route::patch('boq/{boq}', [\App\Http\Controllers\BoqController::class, 'update'])->name('boq.update');
        Route::delete('boq/{boq}', [\App\Http\Controllers\BoqController::class, 'destroy'])->name('boq.destroy');
        
        // Manage details route - comprehensive view of all section and details
        Route::get('boq/{boq}/manage-details', [\App\Http\Controllers\BoqController::class, 'manageDetails'])->name('boq.manage-details');
        
        // Excel upload routes for BOQ
        Route::get('boq/{boq}/upload', [\App\Http\Controllers\BoqController::class, 'showUploadForm'])->name('boq.upload');
        Route::post('boq/{boq}/upload', [\App\Http\Controllers\BoqController::class, 'uploadExcel'])->name('boq.upload.process');
        
        // Download routes for BOQ
        Route::get('boq/{boq}/download/excel', [\App\Http\Controllers\BoqController::class, 'downloadExcel'])->name('boq.download.excel');
        Route::get('boq/{boq}/download/pdf', [\App\Http\Controllers\BoqController::class, 'downloadPdf'])->name('boq.download.pdf');
        
        // Section routes for BOQ management
        Route::get('boq/{boq}/sections/create', [SectionController::class, 'create'])->name('boq.sections.create');
        Route::post('boq/{boq}/sections', [SectionController::class, 'store'])->name('boq.sections.store');
        Route::get('boq/{boq}/sections/{section}/edit', [SectionController::class, 'edit'])->name('boq.sections.edit');
        Route::patch('boq/{boq}/sections/{section}', [SectionController::class, 'update'])->name('boq.sections.update');
        Route::delete('boq/{boq}/sections/{section}', [SectionController::class, 'destroy'])->name('boq.sections.destroy');
    });

    // Detail routes for sections - nested under projects (Admin/PM only)
    Route::prefix('projects/{project}/sections/{section}')->name('sections.')->group(function () {
        Route::get('details', [DetailController::class, 'index'])->name('details.index');
        Route::get('details/create', [DetailController::class, 'create'])->name('details.create');
        Route::post('details', [DetailController::class, 'store'])->name('details.store');
        Route::get('details/{detail}', [DetailController::class, 'show'])->name('details.show');
        Route::get('details/{detail}/edit', [DetailController::class, 'edit'])->name('details.edit');
        Route::patch('details/{detail}', [DetailController::class, 'update'])->name('details.update');
        Route::delete('details/{detail}', [DetailController::class, 'destroy'])->name('details.destroy');
    });

    // Tahapan routes with CRUD privilege checking
    Route::prefix('tahapans')->name('tahapans.')->group(function () {
        // Tahapan routes with CRUD privilege checking  
        Route::get('/', function() { return 'Tahapan Index'; })->name('index');
        // Add other tahapan routes as needed
    });

    // Request routes - can be handled under tools/project category
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/document/{documentNo}', [RequestController::class, 'show'])->name('show')->where('documentNo', '.*')->middleware('admin:tools_view');
        Route::put('/{requestId}', [RequestController::class, 'update'])->name('update')->middleware('admin:tools_edit');
        Route::get('/check-access/{documentNo}', [RequestController::class, 'checkAccess'])->name('check-access')->where('documentNo', '.*')->middleware('admin:tools_view');
    });

    // API endpoints with privilege checking
    Route::get('/api/documents/for-assignment', [DocumentController::class, 'getForAssignment'])->name('api.documents.assignment')->middleware('admin:document_view');

    // Bulk assignment routes - requires tools update privilege
    Route::patch('/projects/{project}/tools/process-bulk-assign', [ToolController::class, 'processBulkAssign'])->name('projects.tools.process-bulk-assign')->middleware('admin:tools_edit');
    Route::patch('/projects/{project}/tools/direct-bulk-assign', [ToolController::class, 'processBulkAssign'])->name('direct.bulk.assign')->middleware('admin:tools_edit');
    
    // BOQ validation API endpoint
    Route::post('/projects/{project}/tools/validate-boq', [ToolController::class, 'validateBoq'])->name('projects.tools.validate-boq')->middleware('admin:tools_add');
});

// Admin system only - no user authentication routes needed



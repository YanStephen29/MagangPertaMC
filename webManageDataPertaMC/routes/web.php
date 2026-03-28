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
use App\Models\Tool;
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
// Handle GET logout requests (fallback for direct URL access)
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout.get');


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

    Route::get('bidangs/{kodeGL}/tools', [BidangController::class, 'toolsForGL'])
        ->name('bidangs.tools')
        ->middleware('admin:kode_bidang_view');

    Route::get('bidangs/tools/{tool}/details', [BidangController::class, 'showToolDetails'])
        ->name('bidangs.tools.details')
        ->middleware('admin:kode_bidang_view');
    
    Route::post('/bidangs/export', [BidangController::class, 'exportExcel'])
        ->name('bidangs.export')
        ->middleware('admin:kode_bidang_view');
        
    // Document routes with CRUD privilege checking
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index')->middleware('admin:document_view');
        Route::get('/create', [DocumentController::class, 'create'])->name('create')->middleware('admin:document_add');
        Route::post('/', [DocumentController::class, 'store'])->name('store')->middleware('admin:document_add');
        Route::get('/after-create/{project}', [DocumentController::class, 'afterCreate'])->name('after-create')->middleware('admin:document_add');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show')->middleware('admin:document_view');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit')->middleware('admin:document_edit');
        Route::patch('/{document}', [DocumentController::class, 'update'])->name('update')->middleware('admin:document_edit');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy')->middleware('admin:document_delete');
    });

    // Tool routes with CRUD privilege checking
    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        // Tool routes with CRUD privilege checking
        Route::get('tools', [ToolController::class, 'index'])->name('tools.index')->middleware('admin:tools_read');
        Route::get('tools/select-boq-items', [ToolController::class, 'selectBoqItems'])->name('tools.select-boq-items')->middleware('admin:tools_create');
        Route::post('tools/process-boq-selection', [ToolController::class, 'processBoqSelection'])->name('tools.process-boq-selection')->middleware('admin:tools_create');
        Route::get('tools/create', [ToolController::class, 'create'])->name('tools.create')->middleware('admin:tools_create');
        Route::post('tools', [ToolController::class, 'store'])->name('tools.store')->middleware('admin:tools_create');
        Route::get('tools/{tool}', [ToolController::class, 'show'])->name('tools.show')->middleware('admin:tools_read');
        Route::get('tools/{tool}/edit', [ToolController::class, 'edit'])->name('tools.edit')->middleware('admin:tools_update');
        Route::patch('tools/{tool}', [ToolController::class, 'update'])->name('tools.update')->middleware('admin:tools_update');
        Route::put('tools/{tool}', [ToolController::class, 'update'])->name('tools.update.put')->middleware('admin:tools_update');
        Route::delete('tools/{tool}', [ToolController::class, 'destroy'])->name('tools.destroy')->middleware('admin:tools_delete');
        Route::patch('tools/{tool}/quick-assign', [ToolController::class, 'quickAssignDocument'])->name('tools.quick-assign')->middleware('admin:tools_update');
        Route::post('tools/assign-document-request', [ToolController::class, 'assignDocumentRequest'])->name('tools.assign-document-request')->middleware('admin:tools_update');
        Route::post('tools/assign-request', [ToolController::class, 'assignRequest'])->name('tools.assign-request')->middleware('admin:tools_update');
        Route::post('tools/create-and-assign-request', [ToolController::class, 'createAndAssignRequest'])->name('tools.create-and-assign-request')->middleware('admin:tools_create');
        
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
        Route::get('boq/{boq}/download/excel/boq-only', [\App\Http\Controllers\BoqController::class, 'downloadBoqOnly'])->name('boq.download.excel.boq-only');
        Route::get('boq/{boq}/download/excel/boq-with-actual', [\App\Http\Controllers\BoqController::class, 'downloadBoqWithActual'])->name('boq.download.excel.boq-with-actual');
        Route::get('boq/{boq}/download/pdf', [\App\Http\Controllers\BoqController::class, 'downloadPdf'])->name('boq.download.pdf');
        
        // Lock BOQ route
        Route::post('boq/{boq}/lock', [\App\Http\Controllers\BoqController::class, 'lockBoq'])->name('boq.lock');
        // Unlock BOQ route
        Route::post('boq/{boq}/unlock', [\App\Http\Controllers\BoqController::class, 'unlock'])->name('boq.unlock');
        
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
        // CREATE NEW REQUEST ROUTE
        Route::post('/create', [RequestController::class, 'store'])->name('store')->middleware('admin:tools_create');
        
        // MORE SPECIFIC ROUTES FIRST (to avoid conflicts with generic /{requestId} routes)
        Route::get('/document/{documentNo}/select-boq-items', [RequestController::class, 'selectBoqItems'])->name('select-boq-items')->where('documentNo', '.*')->middleware('admin:tools_read');
        Route::post('/document/{documentNo}/process-boq-selection', [RequestController::class, 'processBoqSelection'])->name('process-boq-selection')->where('documentNo', '.*')->middleware('admin:tools_update');
        Route::get('/document/{documentNo}', [RequestController::class, 'show'])->name('show')->where('documentNo', '.*')->middleware('admin:tools_read');
        Route::get('/check-access/{documentNo}', [RequestController::class, 'checkAccess'])->name('check-access')->where('documentNo', '.*')->middleware('admin:tools_read');
        
        // GENERIC ROUTES LAST (these can catch anything, so put them at the end)
        Route::put('/{requestId}', [RequestController::class, 'update'])->name('update')->middleware('admin:tools_update');
        Route::post('/{requestId}/update', [RequestController::class, 'update'])->name('update-alt')->middleware('admin:tools_update'); // Alternative POST route for debugging
        Route::patch('/{requestId}/status', [RequestController::class, 'updateStatus'])->name('update-status')->middleware('admin:tools_update'); // Status-only update
        Route::post('/{requestId}/status', [RequestController::class, 'updateStatus'])->name('update-status-alt')->middleware('admin:tools_update'); // Alternative POST route for status
        Route::get('/{requestId}/details', [RequestController::class, 'showDetails'])->name('details')->middleware('admin:tools_read');
    });

    // Hold Requests routes - for admin approval of requests that exceed BOQ quantity
    Route::prefix('hold-requests')->name('hold-requests.')->group(function () {
        Route::get('/', [ToolController::class, 'holdRequests'])->name('index')->middleware('admin:admin_only');
        Route::patch('/{tool}', [ToolController::class, 'updateHoldRequest'])->name('update')->middleware('admin:admin_only');
    });

    // API endpoints with privilege checking
    Route::get('/api/documents/for-assignment', [DocumentController::class, 'getForAssignment'])->name('api.documents.assignment')->middleware('admin:document_view');
    Route::get('/admin/api/requests', [RequestController::class, 'getRequestsForApi'])->name('api.requests')->middleware('admin:tools_read');


    // Bulk assignment routes - requires tools update privilege
    Route::patch('/projects/{project}/tools/process-bulk-assign', [ToolController::class, 'processBulkAssign'])->name('projects.tools.process-bulk-assign')->middleware('admin:tools_update');
    Route::patch('/projects/{project}/tools/direct-bulk-assign', [ToolController::class, 'processBulkAssign'])->name('direct.bulk.assign')->middleware('admin:tools_update');
    
    // BOQ validation API endpoint
    Route::post('/projects/{project}/tools/validate-boq', [ToolController::class, 'validateBoq'])->name('projects.tools.validate-boq')->middleware('admin:tools_create');
    
    // Clear BOQ session route
    Route::post('/clear-boq-session', function (Request $request) {
        session()->forget('selected_boq_items_for_tool');
        return response()->json(['status' => 'success', 'message' => 'BOQ session cleared']);
    })->name('clear-boq-session');
    
    // Tool-Detail relationship testing routes
    Route::prefix('tool-details')->name('tool-details.')->group(function () {
        Route::post('/store-with-details', [\App\Http\Controllers\ToolDetailController::class, 'storeToolWithDetails'])->name('store-with-details')->middleware('admin:tools_create');
        Route::get('/{tool}/details', [\App\Http\Controllers\ToolDetailController::class, 'getToolWithDetails'])->name('get-with-details')->middleware('admin:tools_read');
        Route::post('/{tool}/add-detail', [\App\Http\Controllers\ToolDetailController::class, 'addDetailToTool'])->name('add-detail')->middleware('admin:tools_update');
        Route::get('/{tool}/list-details', [\App\Http\Controllers\ToolDetailController::class, 'getDetailsByTool'])->name('list-details')->middleware('admin:tools_read');
    });
    
    // Debug BOQ routes
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('/boq-usage', [\App\Http\Controllers\DebugBoqController::class, 'debugBoqUsage'])->name('boq-usage')->middleware('admin:tools_read');
        Route::get('/project-boq/{projectId}', [\App\Http\Controllers\DebugBoqController::class, 'debugProjectBoq'])->name('project-boq')->middleware('admin:tools_read');
    });
});

// Admin system only - no user authentication routes needed

// Admin system only - no user authentication routes needed



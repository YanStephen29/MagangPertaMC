<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\Bidang;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Explicit model binding untuk Project dengan key no_IO
        Route::model('project', Project::class);
        Route::bind('project', function ($value) {
            return Project::where('no_IO', $value)->firstOrFail();
        });

        // Explicit model binding untuk Bidang dengan key kode_GL
        Route::model('bidang', Bidang::class);
        Route::bind('bidang', function ($value) {
            return Bidang::where('kode_GL', $value)->firstOrFail();
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('configs')) {
                $activeYear = (request()->hasSession() && session()->has('year')) ? session('year') : env('APP_YEAR', '2568');
                $config = \App\Configs::where('schoolgroup', 0)->where('app_year', $activeYear)->first();
                if (!$config) {
                    $config = \App\Configs::where('schoolgroup', 0)->first();
                }
                if ($config) {
                    // Dynamically set Laravel config defaults
                    config([
                        'app.name' => $config->app_name ?: config('app.name'),
                        'app.year' => $config->app_year ?: env('APP_YEAR', '2570'),
                    ]);

                    // Initialize default session values for backward compatibility in views
                    if (request()->hasSession()) {
                        session([
                            'app_name'  => $config->app_name,
                            'app_group' => $config->app_group,
                            'app_area'  => $config->app_area,
                            'app_year'  => $config->app_year,
                        ]);
                        if (!session()->has('manual_year')) {
                            session(['year' => $config->app_year]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback silently if DB is not ready or migrated
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\Schema;
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
            \Illuminate\Support\Facades\URL::defaults(['eventNumber' => active_event_number()]);
        } catch (\Throwable $e) {}

        EventFacade::listen('eloquent.creating: *', function (string $eventName, array $models): void {
            static $eventColumns = [];
            $model = $models[0] ?? null;

            if (! $model instanceof Model || active_event_id() === null) {
                return;
            }

            $table = $model->getTable();
            $connection = $model->getConnectionName() ?: config('database.default');
            $cacheKey = $connection . ':' . $table;

            if (! array_key_exists($cacheKey, $eventColumns)) {
                $eventColumns[$cacheKey] = Schema::connection($connection)->hasColumn($table, 'event_id');
            }

            if ($eventColumns[$cacheKey] && empty($model->event_id)) {
                $model->event_id = active_event_id();
            }
        });

        // บันทึกกิจกรรม (audit log): การเพิ่ม/แก้/ลบข้อมูลผ่าน Eloquent ทุกโมเดล และการเข้า/ออกจากระบบ
        foreach (['created', 'updated', 'deleted'] as $auditAction) {
            EventFacade::listen('eloquent.'.$auditAction.': *', function (string $eventName, array $models) use ($auditAction): void {
                $model = $models[0] ?? null;
                if ($model instanceof Model) {
                    \App\Services\AuditLogger::model($auditAction, $model);
                }
            });
        }
        EventFacade::listen(\Illuminate\Auth\Events\Login::class, fn ($e) => \App\Services\AuditLogger::record('login', [
            'user_id' => $e->user->getAuthIdentifier(), 'username' => $e->user->email ?? null, 'description' => 'เข้าสู่ระบบ',
        ]));
        EventFacade::listen(\Illuminate\Auth\Events\Logout::class, fn ($e) => \App\Services\AuditLogger::record('logout', [
            'user_id' => $e->user?->getAuthIdentifier(), 'username' => $e->user->email ?? null, 'description' => 'ออกจากระบบ',
        ]));
        EventFacade::listen(\Illuminate\Auth\Events\Failed::class, fn ($e) => \App\Services\AuditLogger::record('login_failed', [
            'username' => $e->credentials['email'] ?? ($e->credentials['username'] ?? null), 'description' => 'เข้าสู่ระบบไม่สำเร็จ (ชื่อบัญชีหรือรหัสผ่านไม่ถูกต้อง)',
        ]));

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('configs')) {
                $event = active_event();
                $activeYearModel = \App\Years::where('active', 1)->first();
                $activeYear = $event?->academic_year
                    ?: ($activeYearModel ? $activeYearModel->years : '2568');

                $configQuery = \App\Configs::where('schoolgroup', 0);
                if ($event && \Illuminate\Support\Facades\Schema::hasColumn('configs', 'event_id')) {
                    $configQuery->where('event_id', $event->id);
                } else {
                    $configQuery->where('app_year', $activeYear);
                }
                $config = $configQuery->first();
                if (!$config) {
                    $config = \App\Configs::where('schoolgroup', 0)
                        ->where('app_year', $activeYear)
                        ->first();
                }
                if ($config) {
                    $themePrimary = !empty($config->theme_primary) ? $config->theme_primary : '#059669';
                    $themeSecondary = !empty($config->theme_secondary) ? $config->theme_secondary : '#0f172a';

                    // Dynamically set Laravel config defaults
                    config([
                        'app.name' => $config->app_name ?: config('app.name'),
                        'app.group' => $config->app_group,
                        'app.area' => $config->app_area,
                        'app.year' => $config->app_year ?: env('APP_YEAR', '2570'),
                        'app.theme_primary' => $themePrimary,
                        'app.theme_secondary' => $themeSecondary,
                    ]);

                    // Initialize default session values for backward compatibility in views
                    if (request()->hasSession()) {
                        session([
                            'app_name'  => $config->app_name,
                            'app_group' => $config->app_group,
                            'app_area'  => $config->app_area,
                            'app_year'  => $config->app_year,
                            'theme_primary' => $themePrimary,
                            'theme_secondary' => $themeSecondary,
                        ]);
                        session(['year' => $activeYear]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback silently if DB is not ready or migrated
        }
    }
}

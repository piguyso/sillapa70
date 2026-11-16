<?php

namespace Tests\Feature;

use App\Http\Middleware\StripEventRouteParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_event_selector_route_is_registered(): void
    {
        $route = Route::getRoutes()->match(Request::create('/', 'GET'));

        $this->assertSame('event.select', $route->getName());
    }

    public function test_each_event_has_its_own_home_route(): void
    {
        foreach ([73, 74] as $eventNumber) {
            $route = Route::getRoutes()->match(Request::create("/{$eventNumber}", 'GET'));

            $this->assertSame(
                'App\\Http\\Controllers\\HomeController@index',
                $route->getActionName(),
            );
            $this->assertSame((string) $eventNumber, $route->parameter('eventNumber'));
        }
    }

    public function test_event_prefix_is_not_passed_to_controller_arguments(): void
    {
        $request = Request::create('/75/config/events/3/activate', 'POST');
        $route = Route::getRoutes()->match($request);
        $request->setRouteResolver(fn () => $route);

        $this->assertSame('75', $route->parameter('eventNumber'));

        (new StripEventRouteParameter())->handle($request, function ($request) {
            $this->assertNull($request->route('eventNumber'));

            return response('ok');
        });
    }

    public function test_database_copy_routes_require_privileged_authentication(): void
    {
        $route = Route::getRoutes()->getByName('copydata.index');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('systemadmin', $route->gatherMiddleware());
    }

    public function test_database_transfer_routes_require_privileged_authentication(): void
    {
        $route = Route::getRoutes()->getByName('databackup');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('systemadmin', $route->gatherMiddleware());
    }

    public function test_network_score_routes_require_network_scorekeeper_role(): void
    {
        $route = Route::getRoutes()->getByName('areacentersillapa.store');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('score.network', $route->gatherMiddleware());
        $this->assertContains('schoolgroup.context', $route->gatherMiddleware());
    }

    public function test_district_score_routes_require_district_scorekeeper_role(): void
    {
        $route = Route::getRoutes()->getByName('centersillapa.store');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('score.district', $route->gatherMiddleware());
    }

    public function test_title_mutations_require_system_administrator(): void
    {
        foreach (['title.store', 'title.update', 'title.destroy'] as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains('systemadmin', $route->gatherMiddleware());
        }
    }

    public function test_shared_reference_data_requires_system_administrator(): void
    {
        foreach (['school', 'schoolgroup', 'branch', 'class', 'dayofsillapa', 'myuser'] as $resource) {
            foreach (['index', 'store', 'edit', 'update', 'destroy'] as $action) {
                $route = Route::getRoutes()->getByName("{$resource}.{$action}");
                $this->assertNotNull($route, "Missing route {$resource}.{$action}");
                $this->assertContains('systemadmin', $route->gatherMiddleware(), "{$resource}.{$action} is not admin-only");
            }
        }
    }

    public function test_school_import_requires_system_administrator(): void
    {
        foreach (['school.import.template', 'school.import.inspect', 'school.import.store'] as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route);
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains('systemadmin', $route->gatherMiddleware());
        }
    }

    public function test_activity_and_subject_mutations_require_system_administrator(): void
    {
        foreach (['activity', 'subjectgroup'] as $resource) {
            foreach (['store', 'edit', 'update', 'destroy'] as $action) {
                $route = Route::getRoutes()->getByName("{$resource}.{$action}");
                $this->assertNotNull($route);
                $this->assertContains('systemadmin', $route->gatherMiddleware());
            }
        }
    }

    public function test_network_user_management_requires_network_admin_and_context(): void
    {
        foreach ([
            ['GET', '/73/area/areauser'],
            ['GET', '/73/area/myuser/2/edit'],
            ['PUT', '/73/myuserareaupdate'],
        ] as [$method, $uri]) {
            $route = Route::getRoutes()->match(Request::create($uri, $method));
            $middleware = $route->gatherMiddleware();

            $this->assertContains('auth', $middleware);
            $this->assertContains('networkadmin', $middleware);
            $this->assertContains('schoolgroup.context', $middleware);
        }
    }

    public function test_network_registration_routes_require_registration_role_and_context(): void
    {
        foreach (['areaaddschool.store', 'areaaddschool.destroy', 'areaaddstudent.store', 'areaaddstudent.update', 'areaaddstudent.destroy'] as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $middleware = $route->gatherMiddleware();

            $this->assertContains('auth', $middleware);
            $this->assertContains('registration.network', $middleware);
            $this->assertContains('schoolgroup.context', $middleware);
        }
    }

    public function test_school_users_cannot_reach_network_judge_management(): void
    {
        foreach (['areajow.store', 'areajow.update', 'areajow.destroy'] as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $middleware = $route->gatherMiddleware();

            $this->assertContains('operator.network', $middleware);
            $this->assertContains('schoolgroup.context', $middleware);
        }
    }
}

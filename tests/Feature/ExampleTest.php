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
}

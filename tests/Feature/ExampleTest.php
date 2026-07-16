<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_public_home_route_is_registered(): void
    {
        $route = Route::getRoutes()->match(Request::create('/', 'GET'));

        $this->assertSame(
            'App\\Http\\Controllers\\HomeController@index',
            $route->getActionName(),
        );
    }

    public function test_database_copy_routes_require_privileged_authentication(): void
    {
        $route = Route::getRoutes()->getByName('copydata.index');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('centermiddleware', $route->gatherMiddleware());
    }

    public function test_database_transfer_routes_require_privileged_authentication(): void
    {
        $route = Route::getRoutes()->getByName('databackup');

        $this->assertNotNull($route);
        $this->assertContains('auth', $route->gatherMiddleware());
        $this->assertContains('centermiddleware', $route->gatherMiddleware());
    }
}

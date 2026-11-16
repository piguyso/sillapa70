<?php

namespace Tests\Unit;

use App\Http\Middleware\NetworkOperatorMiddleware;
use App\Http\Middleware\NetworkRegistrationMiddleware;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    public function test_school_user_can_manage_registration_but_not_judges(): void
    {
        $request = $this->requestForPosition(0);

        $response = (new NetworkRegistrationMiddleware())->handle($request, fn () => response('ok'));
        $this->assertSame(200, $response->getStatusCode());

        $this->expectException(HttpException::class);
        (new NetworkOperatorMiddleware())->handle($request, fn () => response('ok'));
    }

    public function test_scorekeeper_cannot_manage_registration_but_can_manage_judges(): void
    {
        $request = $this->requestForPosition(1);

        $response = (new NetworkOperatorMiddleware())->handle($request, fn () => response('ok'));
        $this->assertSame(200, $response->getStatusCode());

        $this->expectException(HttpException::class);
        (new NetworkRegistrationMiddleware())->handle($request, fn () => response('ok'));
    }

    private function requestForPosition(int $position): Request
    {
        $user = new User();
        $user->position = $position;
        $request = Request::create('/73/test', 'GET');
        $request->setUserResolver(fn () => $user);

        return $request;
    }
}

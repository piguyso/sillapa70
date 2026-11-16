<?php

namespace App\Http\Middleware;

use App\Services\CompetitionAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompetitionAccessMiddleware
{
    public function __construct(private CompetitionAccessService $access) {}

    public function handle(Request $request, Closure $next, string $level): Response
    {
        $user = $request->user();
        if (! $user || (int) $user->position >= 10) {
            return $next($request);
        }

        $action = strtolower((string) optional($request->route())->getActionMethod());
        $isWriteScreen = in_array($action, ['create', 'edit', 'addaddschool', 'addaddstudent',
            'centereditactiviy', 'centereditstudent', 'centereditstudentname', 'addjow', 'addjow2'], true);
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true) && ! $isWriteScreen) {
            return $next($request);
        }

        $rule = $this->access->blockingRule($user, $level);
        if (! $rule) {
            return $next($request);
        }

        $message = 'ระบบปิดการแก้ไขข้อมูลแล้ว กรุณาติดต่อผู้ดูแลระบบ';
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 423);
        }

        return response()->view('errors.systemstop', ['accessMessage' => $message], 423);
    }
}

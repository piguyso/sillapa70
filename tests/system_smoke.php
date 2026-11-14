<?php

declare(strict_types=1);

use App\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(ConsoleKernel::class)->bootstrap();
$kernel = $app->make(Kernel::class);

$first = static function (string $table, string $column = 'id', mixed $fallback = 1): mixed {
    try {
        $query = DB::table($table)->whereNotNull($column);
        if (Schema::hasColumn($table, 'event_id')) {
            $query->where('event_id', 2);
        }

        return $query->value($column) ?? $fallback;
    } catch (Throwable) {
        return $fallback;
    }
};

$values = [
    'eventNumber' => 74,
    'year' => 2569,
    'score' => 80,
    'inx' => 1,
    'area' => $first('schoolgroups'),
    'school_group' => $first('schoolgroups'),
    'Notice' => $first('notices'),
    'notice' => $first('notices'),
    'activity' => DB::table('activities')->where('year', '2569')->value('id') ?? $first('activities'),
    'addschool' => $first('addschools'),
    'addstudent' => $first('addstudents'),
    'areajow' => $first('jows_areas'),
    'branch' => DB::table('activities')->where('year', '2569')->value('activity_branch') ?? $first('branches'),
    'class' => $first('classes'),
    'config' => $first('configs'),
    'dayofsillapa' => $first('dayofsillapas'),
    'jow' => $first('jows'),
    'myuser' => $first('users'),
    'school' => $first('schools'),
    'schoolgroup' => $first('schoolgroups'),
    'subjectgroup' => DB::table('activities')->where('year', '2569')->value('activity_subject') ?? $first('subjectgroups'),
    'systemactive' => $first('systemactives'),
    'title' => $first('titles'),
    'event' => $first('events'),
    'file' => 'missing-smoke-test-file.pdf',
    'type' => 'picture',
    'token' => 'invalid-smoke-token',
];

$contextId = static function (string $uri) use ($first): mixed {
    return match (true) {
        str_contains($uri, 'notice') => $first('notices'),
        str_contains($uri, 'centereditstudentname/') => str_contains($uri, '/area/') ? $first('addstudents_areas') : $first('addstudents'),
        str_contains($uri, 'centereditstudent/'), str_contains($uri, '/area/addaddstudent/') => str_contains($uri, '/area/') ? $first('addschools_areas') : $first('addschools'),
        str_contains($uri, '/addaddstudent/') => $first('addschools'),
        str_contains($uri, 'areajow2/') => DB::table('jows_areas')->where('jow_type', 1)->value('id') ?? $first('jows_areas'),
        str_contains($uri, 'areajow/') && str_contains($uri, '/edit') => $first('jows_areas'),
        str_contains($uri, 'jow2/') && str_contains($uri, '/edit') => DB::table('jows')->where('jow_type', 1)->value('id') ?? $first('jows'),
        str_contains($uri, 'areasubjectgroup') => DB::table('activities')->where('event_id', 2)->value('activity_subject') ?? $first('subjectgroups'),
        preg_match('~/(?:view|addactivity)/\{id\}$~', $uri) === 1 => DB::table('activities')->where('event_id', 2)->value('activity_subject') ?? $first('subjectgroups'),
        str_contains($uri, '/addjow/') => $first('activities'),
        str_contains($uri, '/area/jow2registerform/') => $first('schoolgroups'),
        str_contains($uri, 'addaddschool/getschool/') => $first('schoolgroups'),
        str_contains($uri, 'showschooldetail'), str_contains($uri, 'showschool/') => $first('schoolgroups'),
        str_contains($uri, 'viewactivity/') && !str_contains($uri, 'detail') => DB::table('activities')->where('year', '2569')->value('activity_subject') ?? $first('subjectgroups'),
        str_contains($uri, 'reportviewactivity/') => DB::table('activities')->where('year', '2569')->value('activity_subject') ?? $first('subjectgroups'),
        str_contains($uri, 'area/') && preg_match('~/area/\{id\}$~', $uri) => $first('schoolgroups'),
        str_contains($uri, 'myuser/') => $first('users'),
        str_contains($uri, 'jow2') => DB::table('activities')->where('year', '2569')->value('activity_branch') ?? $first('branches'),
        default => DB::table('activities')->where('year', '2569')->value('id') ?? $first('activities'),
    };
};

$users = ['guest' => null];
foreach ([0, 1, 5, 10] as $position) {
    $users['position_'.$position] = User::withoutGlobalScopes()->where('position', $position)->first();
}

$routes = collect(app('router')->getRoutes()->getRoutes())
    ->filter(fn ($route) => in_array('GET', $route->methods(), true))
    ->filter(fn ($route) => !str_starts_with($route->uri(), 'cpn1/'))
    ->filter(fn ($route) => $route->uri() !== 'up')
    // Legacy PDF/Excel controllers call exit() after streaming. They are
    // exercised separately so one stream cannot abort the entire crawl.
    ->filter(fn ($route) => !preg_match('~(?:print|exportexcel|secure-assets)~i', $route->uri()))
    ->values();

$results = [];
foreach ($routes as $route) {
    $uri = $route->uri();
    $path = preg_replace_callback('/\{([^}?]+)\??\}/', function (array $match) use ($values, $contextId, $uri) {
        $name = $match[1];
        return rawurlencode((string) ($values[$name] ?? ($name === 'id' ? $contextId($uri) : 1)));
    }, $uri);

    foreach ($users as $role => $user) {
        if ($user === null) {
            Auth::forgetUser();
        } else {
            Auth::setUser($user);
        }

        $requestUri = $path === '/' ? '/' : '/'.ltrim($path, '/');
        $request = Request::create($requestUri, 'GET', [], [], [], [
            'HTTP_HOST' => 'sillapa.cpn1.go.th',
            'HTTPS' => 'on',
        ]);
        $request->setUserResolver(fn () => $user);
        $started = microtime(true);
        $emitted = '';
        try {
            ob_start();
            $response = $kernel->handle($request);
            $emitted = (string) ob_get_clean();
            $status = $response->getStatusCode();
            $bytes = strlen((string) $response->getContent()) + strlen($emitted);
            $location = $response->headers->get('Location');
            $error = null;
            $kernel->terminate($request, $response);
        } catch (Throwable $e) {
            if (ob_get_level() > 0) {
                $emitted = (string) ob_get_clean();
            }
            $status = 599;
            $bytes = strlen($emitted);
            $location = null;
            $error = get_class($e).': '.$e->getMessage();
        }
        $results[] = [
            'role' => $role,
            'method' => 'GET',
            'uri' => '/'.$path,
            'route_name' => $route->getName(),
            'status' => $status,
            'ms' => (int) round((microtime(true) - $started) * 1000),
            'bytes' => $bytes,
            'location' => $location,
            'error' => $error,
        ];
    }
}

$summary = [
    'generated_at' => date(DATE_ATOM),
    'route_count' => $routes->count(),
    'request_count' => count($results),
    'status_counts' => array_count_values(array_map(fn ($row) => (string) $row['status'], $results)),
    'server_errors' => array_values(array_filter($results, fn ($row) => $row['status'] >= 500)),
    'results' => $results,
];

$target = dirname(__DIR__).'/storage/app/system-smoke-results.json';
file_put_contents($target, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo json_encode(array_diff_key($summary, ['results' => true]), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PHP_EOL;
echo "Results: {$target}", PHP_EOL;

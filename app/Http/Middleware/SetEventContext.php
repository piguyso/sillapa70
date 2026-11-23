<?php

namespace App\Http\Middleware;

use App\Configs;
use App\Event;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetEventContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $isCpn1 = str_contains($request->getRequestUri(), '/cpn1/') || $request->segment(1) === 'cpn1';

        $eventNumber = (int) $request->segment(1);
        if ($isCpn1 && $request->segment(1) === 'cpn1') {
            $eventNumber = (int) $request->segment(2);
        }

        if ($eventNumber < 1
            && preg_match('~/cpn1/([0-9]+)(?:/|$)~', $request->getRequestUri(), $matches)) {
            $eventNumber = (int) $matches[1];
        }

        if ($eventNumber < 1) {
            return $next($request);
        }

        $event = Event::where('event_number', $eventNumber)->firstOrFail();

        config([
            'app.event_number' => $eventNumber,
            'app.year' => $event->academic_year,
        ]);

        URL::defaults(['eventNumber' => $eventNumber]);
        $needsCpn1InPath = $request->segment(1) === 'cpn1' && !str_contains($request->getBaseUrl(), '/cpn1');
        URL::formatPathUsing(function (string $path, $route = null) use ($eventNumber, $needsCpn1InPath): string {
            // Named routes already contain the dynamic event prefix. Plain url()
            // calls from legacy views need the prefix added centrally.
            // Public assets are shared by every event and must remain outside the
            // /{eventNumber} prefix (for example /cpn1/imgs/silver.png).
            $isPublicAsset = preg_match(
                '~^/(?:adminlte|css|dist|downloads|fonts1?|imgs|js|upload)(?:/|$)~i',
                $path
            );

            if ($route !== null
                || $isPublicAsset
                || preg_match('~^/(?:cpn1/)?[0-9]+(?:/|$)~', $path)) {
                return $path;
            }

            $prefix = ($needsCpn1InPath ? '/cpn1/' : '/') . $eventNumber;

            return $prefix . ($path === '/' ? '' : $path);
        });

        $config = Configs::where('event_id', $event->id)
            ->where('schoolgroup', 0)
            ->first();

        $themePrimary = ($config && !empty($config->theme_primary)) ? $config->theme_primary : '#059669';
        $themeSecondary = ($config && !empty($config->theme_secondary)) ? $config->theme_secondary : '#0f172a';

        config([
            'app.event_id' => $event->id,
            'app.event_number' => $eventNumber,
            'app.year' => $event->academic_year,
            'app.name' => ($config && $config->app_name) ? $config->app_name : config('app.name'),
            'app.group' => $config->app_group ?? '',
            'app.area' => $config->app_area ?? '',
            'app.network_label' => $config->network_label ?? '',
            'app.theme_primary' => $themePrimary,
            'app.theme_secondary' => $themeSecondary,
        ]);

        if ($config && $request->hasSession()) {
            session([
                'year' => $event->academic_year,
                'app_year' => $event->academic_year,
                'app_name' => $config->app_name,
                'app_group' => $config->app_group,
                'app_area' => $config->app_area,
                'theme_primary' => $themePrimary,
                'theme_secondary' => $themeSecondary,
                'event_number' => $eventNumber,
                'event_id' => $event->id,
            ]);
        }

        $request->route()?->forgetParameter('eventNumber');

        return $next($request);
    }
}

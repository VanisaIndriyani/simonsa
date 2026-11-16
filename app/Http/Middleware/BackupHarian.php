<?php

namespace App\Http\Middleware;

use App\Services\BackupService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BackupHarian
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->runningUnitTests()) {
            return $response;
        }

        Bus::dispatchAfterResponse(function () {
            try {
                Cache::lock('simonsa-backup', 30)->block(2, function () {
                    app(BackupService::class)->otomatisJikaPerlu();
                });
            } catch (Throwable $e) {
                report($e);
            }
        });

        return $response;
    }
}

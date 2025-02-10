<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckPackageActivation
{
    public function handle($request, Closure $next, $packageName)
    {
        $package = DB::table('packages')->where('name', $packageName)->first();

        if (!$package || $package->status !== 'activated') {
            abort(403, 'This package is not activated.');
        }

        return $next($request);
    }
}
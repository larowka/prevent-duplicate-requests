<?php

namespace Larowka\PreventDuplicateRequests\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PreventDuplicateRequests
{
    protected const CACHE_KEY = 'request_hash_%s';

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request  The incoming request instance.
     * @param  Closure  $next  The next middleware closure.
     * @param  int  $seconds  The number of seconds to cache the request key.
     */
    public function handle(Request $request, Closure $next, int $seconds = 5): mixed
    {
        $key = $this->getRequestKey($request);

        if (Cache::has($key)) {
            throw new TooManyRequestsHttpException($seconds, 'Duplicate request detected.');
        }

        Cache::put($key, true, $seconds);

        return $next($request);
    }

    /**
     * Generates a unique key for the request based on its content and user identifier or IP address.
     *
     * @param  Request  $request  The incoming request instance.
     * @return string The generated unique cache key.
     */
    protected function getRequestKey(Request $request): string
    {
        $input = $request->all();

        if (($auth = $request->user()) && $auth instanceof Authenticatable) {
            $user = $auth->getAuthIdentifier();
        } else {
            $user = $request->ip();
        }

        $hash = md5(sprintf('%s%s%s', serialize($input), $user, $request->fullUrl()));

        return sprintf(self::CACHE_KEY, $hash);
    }
}

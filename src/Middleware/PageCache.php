<?php

namespace MXJ\PageCache\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class PageCache
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param int|null $minutes
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function handle(Request $request, Closure $next, int $minutes = null)
    {
        if (Auth::user()) {
            return $next($request);
        }
        $key = $request->fullUrl();
        if (strlen($key) >= 240) {
            return $next($request);
        }
        $store = config('pagecache.store');
        $page = Cache::store($store)->get($key);

        if ($page) {
            return response($page);
        }

        if (!$minutes) {
            $minutes = config('pagecache.livetime', 2880); // two days: 60 * 24 * 2 = 2880
        }
        $result = $next($request);
        $page = $result->getContent();
        if (200 == $result->getStatusCode()) {
            Cache::store($store)->put($key, $page, now()->addMinutes($minutes));
        } else {
            return $result;
        }

        return response($page);
    }
}

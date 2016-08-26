<?php

namespace LaravelCMF\Admin\Http\Middleware;

use Closure;
use LaravelCMF\Admin\CMF;

class CMFSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $installed = CMF::get('installed');
        $basename = basename($request->getPathInfo());

        if(!$installed && $basename !== 'install') {
            return redirect(cmf_url('install'));
        }
        return $next($request);
    }
}

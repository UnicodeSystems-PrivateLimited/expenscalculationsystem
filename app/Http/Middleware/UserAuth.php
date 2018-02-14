<?php

namespace App\Http\Middleware;

use App;
use Closure;

class UserAuth {

    protected $response;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $authentication = App::make('authenticator');
        $user = $authentication->getLoggedUser();
        if (!$user || !$user->id) {
            view()->share('isUserLoggedIn', FALSE);
            return redirect()->route('login');
        }
        view()->share('isUserLoggedIn', TRUE);
        return $next($request);
    }

}

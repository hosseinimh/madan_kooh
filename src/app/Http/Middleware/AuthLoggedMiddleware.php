<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCode;
use App\Constants\Theme;
use Closure;
use Illuminate\Http\Request;

// 'user|administrator' type users

class AuthLoggedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!auth()->user()) {
                throw new \Exception(__('user.user_not_found'));
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['_result' => '0', '_error' => $e->getMessage(), '_errorCode' => ErrorCode::USER_NOT_AUTHORIZED], 200);
            }
            return redirect(Theme::LOGIN_URL);
        }
        return $next($request);
    }
}

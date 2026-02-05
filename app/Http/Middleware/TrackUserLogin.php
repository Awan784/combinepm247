<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TrackUserLogin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $updatedUser = User::find($user->id);
            $updatedUser->is_login = 1;
            $updatedUser->save();
        }

        return $next($request);
    }
}

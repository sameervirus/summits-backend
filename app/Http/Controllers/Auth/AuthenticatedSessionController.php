<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $token = $request->authenticate();

        // $request->session()->regenerate();

        $user = Auth::user();

        if($request->has('type') && $request->type == 'admin') {
            if(! $user->hasRole('admin')) {
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }
        }

        return response([
            'token' => $token,
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'user' => $user
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('api')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return response([
            'status' => 'success',
            'message' => __('logout')
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function store(Request $request) {


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        if (! Auth::guard('web')->attempt($request->only('email', 'password'))) {

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }


        $user = Auth::guard('web')->user();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

}

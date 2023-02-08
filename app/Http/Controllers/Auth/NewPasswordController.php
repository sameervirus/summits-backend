<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    /**
     * Handle an incoming change password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'oldPassword' => ['required'],
            'newPassword' => ['required', Rules\Password::defaults()]
        ]);

        $user = Auth::user();

        if($user) {
            if(Hash::check($request->oldPassword, $user->password))
            {
                $user->password = Hash::make($request->newPassword);
                $user->save();

                $status = "Password Changed";
            } else {
                throw ValidationException::withMessages([
                    'email' => __('auth.password'),
                ]);
            }
        }

        return response()->json(['status' => __('passwords.reset')]);
    }
}

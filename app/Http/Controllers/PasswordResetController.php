<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\PasswordReset;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class PasswordResetController extends Controller
{
    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }


    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
                ], 404);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => $this::quickRandom(30)]
        );

        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return response()->json([
            'success' => true,
            'message' => 'We have e-mailed your password reset link!'
        ]);

    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        if (Carbon::parse($passwordReset->updated_at)->addHours(24)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        return view('resetPassword')->with('passwordReset',$passwordReset);
        //return response()->json($passwordReset);
    }


    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token]]
        )->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return view('successReset');
    }
}

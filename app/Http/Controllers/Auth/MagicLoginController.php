<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MagicLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicLoginController extends Controller
{
    public function showForm()
    {
        return view('auth.magic-login');
    }

    public function requestLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }

        $token = Str::random(64);

        MagicLogin::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(15),
        ]);

        $loginUrl = route('magic.login.token', $token);

        Mail::raw("Click here to log in: $loginUrl", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Magic Login Link');
        });

        return back()->with('status', 'Login link sent! Check your email.');
    }

    public function loginViaToken($token)
    {
        $record = MagicLogin::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            abort(403, 'Invalid or expired login link.');
        }

        Auth::login($record->user);
        $record->delete(); // invalidate token

        return redirect()->route('home');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        ]);

        $name = $request->name;
        $email = $request->email;

        $verificationUrl = URL::temporarySignedRoute(
            'verify.registration',
            now()->addMinutes(60),
            ['email' => $email, 'name' => $name]
        );

        Mail::raw("Click to verify and complete your registration: $verificationUrl", function ($message) use ($email) {
            $message->to($email)
                ->subject('Verify your email');
        });

        return redirect()->back()->with('status', 'Verification link sent! Check your email.');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {

        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        $email = $request->email;
        $name = $request->name;

        if (User::where('email', $email)->exists()) {
            return redirect('/')->with('status', 'You are already registered. Please log in.');
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => Carbon::now(),

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/')->with('status', 'You are now registered and logged in!');
    }
}

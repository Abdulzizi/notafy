<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\User;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered; 

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('extract.index'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'                    => $data['name'],
            'email'                   => $data['email'],
            'password'                => Hash::make($data['password']),
            'credits'                 => 10,
            'credits_last_refilled_at' => now(),
        ]);

        event(new Registered($user));
        CreditTransaction::record($user->id, 'bonus', 10, 'Welcome bonus');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('extract.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function googleCallback(Request $request, SocialAuthService $service)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = $service->findOrCreateFromProvider(
                provider: 'google',
                providerUserId: (string) $googleUser->getId(),
                email: $googleUser->getEmail(),
                name: $googleUser->getName() ?: 'User',
                avatar: $googleUser->getAvatar(),
                raw: $googleUser->user ?? []
            );

            Auth::login($user, true);
            $request->session()->regenerate();

            return redirect()->intended(route('extract.index'));
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('status', 'Login Google gagal.');
        }
    }
}

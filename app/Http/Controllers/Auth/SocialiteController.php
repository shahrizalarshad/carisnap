<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(Request $request)
    {
        if ($request->query('as') === 'photographer') {
            session(['register_as_photographer' => true]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $registerAsPhotographer = session()->pull('register_as_photographer', false);

            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user);

                return redirect()->intended($this->postAuthRedirect($user));
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => $registerAsPhotographer ? UserRole::Photographer : UserRole::Client,
                ]);
            }

            Auth::login($user);

            return redirect()->intended($this->postAuthRedirect($user));

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to login using Google. Please try again.',
            ]);
        }
    }

    protected function postAuthRedirect(User $user): string
    {
        if ($user->role === UserRole::Photographer && ! $user->profile) {
            return route('photographer.onboarding', absolute: false);
        }

        return route('dashboard', absolute: false);
    }
}

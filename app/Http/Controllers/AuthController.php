<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function showSignup()
    {
        return view('signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'type' => 'required|in:debutant,experimente',
        ]);

        $otp = random_int(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'otp_code' => $otp,
        ]);

        // Log avant envoi
        Log::info("Tentative d'envoi du code OTP à l'email : {$user->email} (OTP: $otp)");

        try {
            Mail::raw("Votre code de vérification AgriElevage est : $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Code de vérification AgriElevage');
            });
            Log::info("Code OTP envoyé avec succès à {$user->email}");
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi du code OTP à {$user->email} : " . $e->getMessage());
        }

        // Authentifier l'utilisateur mais non vérifié
        Auth::login($user);
        return redirect()->route('verify.otp.form');
    }

    public function showOtpForm()
    {
        return view('verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        // Ensure $user is a fresh Eloquent model instance
        if ($user && $user->otp_code == $request->otp_code) {
            $eloquentUser = User::find($user->id);
            $eloquentUser->email_verified_at = Carbon::now();
            $eloquentUser->otp_code = null;
            $eloquentUser->save();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp_code' => 'Code incorrect.'])->withInput();
    }

    public function showLogin()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les informations de connexion sont incorrectes.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        $otp = random_int(100000, 999999);
        $eloquentUser = \App\Models\User::find($user->id);
        $eloquentUser->otp_code = $otp;
        $eloquentUser->save();

        Log::info("Renvoyer OTP à {$user->email} (OTP: $otp)");

        try {
            Mail::raw("Votre nouveau code de vérification AgriElevage est : $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Nouveau code de vérification AgriElevage');
            });
            Log::info("Nouveau code OTP envoyé avec succès à {$user->email}");
        } catch (\Exception $e) {
            Log::error("Erreur lors du renvoi du code OTP à {$user->email} : " . $e->getMessage());
        }

        return back()->with('resent', true);
    }
}


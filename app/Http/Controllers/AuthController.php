<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Votre compte a été suspendu.');
            }

            return redirect()->intended($this->redirectByRole($user));
        }

        return back()->with('error', 'Identifiants incorrects.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:client,vendeur,livreur',
            'quartier' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'pin' => 'nullable|digits:4',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'pin_hash' => isset($validated['pin']) ? Hash::make($validated['pin']) : null,
            'role' => $validated['role'],
            'quartier' => $validated['quartier'] ?? null,
            'ville' => $validated['ville'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Déconnexion réussie.');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Mot de passe actuel incorrect.');
        }

        auth()->user()->update(['password' => $request->password]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    public function showPinVerify()
    {
        return view('auth.pin-verify');
    }

    public function verifyPin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:4']);

        $user = auth()->user();

        if (!$user->pin_hash || !Hash::check($request->pin, $user->pin_hash)) {
            return back()->with('error', 'Code PIN incorrect.');
        }

        session(['pin_verified' => true, 'pin_verified_at' => now()]);

        $redirect = session('pin_redirect', route('dashboard'));
        session()->forget('pin_redirect');

        return redirect($redirect)->with('success', 'PIN vérifié.');
    }

    public function setupPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4|confirmed',
        ]);

        auth()->user()->update(['pin_hash' => Hash::make($request->pin)]);

        return back()->with('success', 'Code PIN configuré.');
    }

    private function redirectByRole(User $user): string
    {
        return match ($user->role) {
            'administrateur' => route('admin.dashboard'),
            'vendeur' => route('vendor.dashboard'),
            'livreur' => route('delivery.dashboard'),
            default => route('dashboard'),
        };
    }
}

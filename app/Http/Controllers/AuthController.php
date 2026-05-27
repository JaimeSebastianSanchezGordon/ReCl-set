<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'name.required' => 'Ingresa tu nombre.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'email.required' => 'Ingresa tu correo electronico.',
            'email.email' => 'Ingresa un correo electronico valido.',
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'password.required' => 'Ingresa una contrasena.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]);

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json(['data' => $user], Response::HTTP_CREATED);
        }

        return redirect()
            ->route('garments.my')
            ->with('status', 'Cuenta creada correctamente. Ya puedes publicar tus prendas.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Ingresa tu correo electronico.',
            'email.email' => 'Ingresa un correo electronico valido.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withErrors(['email' => 'No pudimos enviar el enlace de recuperacion para ese correo.'])
                ->onlyInput('email');
        }

        return back()->with('status', 'Te enviamos un enlace para restablecer tu contrasena.');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'email' => $request->query('email'),
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ], [
            'email.required' => 'Ingresa tu correo electronico.',
            'email.email' => 'Ingresa un correo electronico valido.',
            'password.required' => 'Ingresa una contrasena.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withErrors(['email' => 'El enlace de recuperacion no es valido o ya expiro.'])
                ->onlyInput('email');
        }

        return redirect()
            ->route('login')
            ->with('status', 'Contrasena actualizada correctamente. Ya puedes iniciar sesion.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresa tu correo electronico.',
            'email.email' => 'Ingresa un correo electronico valido.',
            'password.required' => 'Ingresa tu contrasena.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json(['data' => $request->user()]);
        }

        return redirect()
            ->intended(route('garments.my'))
            ->with('status', 'Sesion iniciada correctamente.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Sesion cerrada correctamente.']);
        }

        return redirect()
            ->route('garments.explore')
            ->with('status', 'Sesion cerrada correctamente.');
    }
}

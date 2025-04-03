<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User; 

class LoginController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     // Validación de los campos del formulario
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     $rememberMe = $request->rememberMe ? true : false;

    //     $user = User::where('email', $credentials['email'])->first();

    //     if (!$user) {
    //         return back()->withErrors([
    //             'email' => 'El usuario no existe',
    //         ])->withInput($request->only('email'));
    //     }

    //     // Si la cuenta está bloqueada, mostrar mensaje y link para restablecer contraseña
    //     if ($user->locked_at && $user->locked_at > now()->subMinutes(30)) {
    //         return back()->withErrors(['email' => 'Tu cuenta está bloqueada debido a múltiples intentos fallidos. <a href="' . route('password.request') . '">Restablecer contraseña</a>']);
    //     }

    //     // Intentar hacer login
    //     if (Auth::attempt($credentials, $rememberMe)) {
    //         // Si el login es exitoso, reiniciar intentos fallidos y desbloquear cuenta
    //         $user->failed_attempts = 0;
    //         $user->locked_at = null;
    //         $user->save();
            
    //         $request->session()->regenerate();
    //         return redirect()->intended('/dashboard');
    //     }

    //     // Incrementar los intentos fallidos
    //     $user->increment('failed_attempts');

    //     // Bloquear la cuenta si llega a 4 intentos fallidos
    //     if ($user->failed_attempts >= 4) {
    //         $user->locked_at = now();
    //         $user->save();

    //         return back()->withErrors([
    //             'password' => 'Demasiados intentos fallidos. Tu cuenta ha sido bloqueada. <a href="' . route('password.request') . '" class="btn btn-link">Restablecer contraseña</a>',
    //         ])->withInput($request->only('email'));
            
    //     }

    //     $user->save();

    //     return back()->withErrors([
    //         'password' => 'Credenciales incorrectas',
    //     ])->withInput($request->only('email'));
    // }

    public function store(Request $request)
{
    // Validación de los campos del formulario
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $rememberMe = $request->rememberMe ? true : false;

    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'El usuario no existe',
        ])->withInput($request->only('email'));
    }

    // Verificamos si la cuenta está bloqueada
    if ($user->failed_attempts >= 4) {
        return back()->withErrors([
            'password' => 'Demasiados intentos fallidos. Tu cuenta ha sido bloqueada.'
        ])->withInput($request->only('email'))->with('account_locked', true);
    }

    if (Auth::attempt($credentials, $rememberMe)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'password' => 'Credenciales incorrectas',
    ])->withInput($request->only('email'));
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/sign-in');
    }
}

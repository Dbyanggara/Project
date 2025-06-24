<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role; // Menggunakan model Role dari Spatie secara langsung

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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:255'
            ],
        ], [
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        try {
            DB::beginTransaction();

            Log::info('Starting user registration process', ['email' => $request->email]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            // Check if 'user' role exists
            if (!Role::where('name', 'user')->exists()) {
                throw new \Exception('Role "user" does not exist in the database');
            }

            // Assign 'user' role after registration
            $user->assignRole('user');
            Log::info('Role assigned successfully', ['user_id' => $user->id, 'role' => 'user']);

            event(new Registered($user));
            event(new UserRegistered($user));
            Log::info('Registration events fired', ['user_id' => $user->id]);

            Auth::login($user);
            Log::info('User logged in successfully', ['user_id' => $user->id]);

            DB::commit();
            Log::info('Registration completed successfully', ['user_id' => $user->id]);

            // Redirect to user dashboard
            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Session\Middleware\StartSession;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            // Mendapatkan data pengguna dari penyedia
            $userSocial = Socialite::driver($provider)->user();

            // Log data pengguna untuk debugging
            Log::info('Callback from provider: ' . $provider);
            Log::info('User data: ', (array) $userSocial);

            // Update atau buat pengguna berdasarkan data yang diterima
            $user = User::updateOrCreate(
                ['email' => $userSocial->getEmail()],
                [
                    'username' => $userSocial->getName(), // Sesuaikan jika perlu
                    'no_telp' => '', // Sesuaikan jika perlu
                    'password' => bcrypt(Str::random(24)), // Password sementara
                    'role' => 'user'
                ]
            );

            // Login pengguna
            Auth::login($user);

            // Mengembalikan token API
            return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);

        } catch (\Exception $e) {
            // Log kesalahan
            Log::error('Socialite error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }
    }

    public function __construct()
    {
        $this->middleware(StartSession::class);
    }

    public function refreshToken($refreshToken)
    {
        try {
            $response = Http::post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'refresh_token',
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'refresh_token' => $refreshToken,
            ]);

            $data = $response->json();

            // Menyimpan atau menggunakan token yang diperbarui
            return response()->json($data);
        } catch (Exception $e) {
            Log::error('Token refresh error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to refresh token.'], 500);
        }
    }
}

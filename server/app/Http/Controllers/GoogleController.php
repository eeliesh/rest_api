<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    use HttpResponses;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $dbUser = User::where('email', $user->email)->first();

            // generate random secure password
            $password = bin2hex(random_bytes(8));
            $password = Hash::make($password);

            if (!$dbUser) {
                $dbUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $password,
                    'google_id' => $user->id
                ]);
            }

            Auth::login($dbUser);

            $dbUser->createToken('auth token for ' . $dbUser->name)->plainTextToken;

            // return $this->success([
            //     'user' => $dbUser,
            //     'token' => $dbUser->createToken('auth token for ' . $dbUser->name)->plainTextToken
            // ]);

            return redirect(env('FRONTEND_URL', 'http://localhost:3000'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

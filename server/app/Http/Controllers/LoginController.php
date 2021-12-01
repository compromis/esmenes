<?php

namespace App\Http\Controllers;
use MiladRahimi\Jwt\Generator;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $token = $request->input('token');
        $key = config('esmenes.jwt_secret');
        $signer = new HS256($key);
        $parser = new Parser($signer);
        $claims = $parser->parse($token);

        // Is user already registered in the app?
        $user = User::where('DNI', $claims['DNI'])->first();
        $token = ($user) ? $user->tokens()->first()->plainTextToken : null;

        // If it exists, update user data
        if ($user) {
            $user->data = json_encode($claims);
            $user->save();
        } else { // If not, create a new user
            $user = User::create([
                'DNI' => $claims['DNI'],
                'email' => $claims['email'],
                'name' => $claims['nom'],
                'last_name' => $claims['cognoms'],
                'password' => bcrypt(Str::random(16)),
                'data' => json_encode($claims)
            ]);
        }

        // Create token if none exists
        if (!$token) {
            $token = $user->createToken('Esmenes')->plainTextToken;
        }

        return redirect(config('esmenes.frontend_url') . '/login?token=' . $token);
    }
}

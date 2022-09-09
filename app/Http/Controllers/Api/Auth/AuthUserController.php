<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends Controller
{
    public function auth(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where([['email', $request->email]])->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['mensagem' => 'Credenciais invalidas'], 404);
        }

        $token = $user->createToken($request->email);

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function me(Request $request){

        $user = User::where('id', $request->user()->id)->first();
        return response()->json($user, 200);
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([], 204);
    }
}

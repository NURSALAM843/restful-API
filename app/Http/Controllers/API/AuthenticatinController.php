<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\API\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatinController extends Controller
{
    public function login(Request $request)
    {
       $request->validate([
            'email' => 'required|email',
            'password' => 'required',
       ]);

       $user = User::where('email', $request->email)->first();
       
       if(! $user || ! Hash::check($request->password, $user->password)){
        throw ValidationException::withMessages([
            'email' => ['Credential Incorrect'],
        ]);
       }

       $data = $user->createToken('user login')->plainTextToken;

       return response()->json(['access_key' => $data]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return "Berhasil Logout";
    }

    public function me(Request $request)
    {

        $user = auth()->user();
        // $post = Post::where("author", $user->id);
        return response()->json($user);
    }
}

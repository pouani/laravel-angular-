<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;



class LoginController extends Controller {
    
   public function register(Request $request)
   {
       $this->validate($request, [
           'name' => 'required|min:4',
           'email' => 'required|email',
           'password' => 'required|min:8',
       ]);

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password)
       ]);
      
       $token = $user->createToken('token')->accessToken;

       return response()->json(['token' => $token], 200);
   }

   public function login(Request $request)
   {
       $data = [
           'email' => $request->email,
           'password' => $request->password
       ];

       if (auth()->attempt($data)) {
           $token = auth()->user()->createToken('token')->accessToken;
           return response()->json([
            'token' => $token,
            'user' => auth()->user()
        ], 200);
       } else {
           return response()->json(['error' => 'Unauthorised'], 401);
       }
   }   
}
<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HSV;
use App\Token;
use Validator;
use DB;

class UsersController extends Controller
{
    public function index(){
        return view("signup");
    }
    public function signup(Request $request){

        $rules = [
            "email" => "required|email",
            "password" => "required",
            "name" => "required|string"
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()){
            return response()->json($validate->errors(), 400);
        }

        $token = new Token;
        $user = HSV::create($request->all());
        $tk = JWTAuth::fromUser($user);

        $token->token = $tk;

        $token->save();



      

          

       // return response()->json(["message" => "Registered Successfully"]);

       return response()->json(compact('user','token'),201);

        // return response()->json(["token" => $token]);
    }

    public function login(Request $request){
        $rules = [
            "email" => "required|email",
            "password" => "required"
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()){
            return response()->json($validate->errors(), 400);
        }

         $email = $request->email;
         $password = $request->password;
        
         $user = DB::table("table__h_s_v_users")->where('email', $request->email)->first();
         $id = $user->id;

        // $user = HSV::where(["email" => $email, "password"=>$password])->get();
      
      
       // $user = HSV::where("email", $email)->get();
        if ($user->email === $email && $user->password === $password){

         //   $token = JWTAuth::attempt($rules);

            $token = Token::where("id", $id)->get();

            return response()->json([
                "email" => $user->email,
                "name" => $user->name,
                "token" => $token            
            ]);
        }else {
            return response()->json(["message" => "Login failed"]);
        }

        // $credentials = $request->only('email', 'password');

        // try {
        //     if (! $token = JWTAuth::attempt($credentials)) {
        //         return response()->json(['error' => 'invalid_credentials'], 400);
        //     }
        // } catch (JWTException $e) {
        //     return response()->json(['error' => 'could_not_create_token'], 500);
        // }

        // return response()->json(compact('token'));






    }

    public function getUser(Request $request){
        //return response()->json(HSV::get(), 200);

        $cre = $request->only('token');

        $token = DB::table("table__token")->where('token', $request->cre)->first();
        // $token = Token::where('token', $cre)->get();

        return response()->json(["id" => $token->id]);

        // $user = DB::table("table__h_s_v_users")->where('id', $token->cre)->first();

        // return response()->json([
        //     "name" => $user->name,
        //     "email" => $user->email
        // ]);


    }
}

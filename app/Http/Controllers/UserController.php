<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }
    
    public function login(Request $request)
    {
		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
                return $this->response->errorResponse('Invalid username and password');
			}
		} catch(JWTException $e){
            return $this->response->errorResponse('Generate Token Failed');
		}

		$user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
			'user' => $user
        ]);
	}

	public function getUser()
	{
		$user = JWTAuth::user();
		return response()->json($user);
	}

    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'username' => 'required',
			'password' => 'required|string|min:6',
			'role' => 'required',
            'id_outlet' => 'required'
		]);

		if($validator->fails()){
            return response()->json($validator->errors());
		}

		$user = new User();
		$user->name 	= $request->name;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role 	= $request->role;
        $user->id_outlet = $request->id_outlet;

		$user->save();

		$token = JWTAuth::fromUser($user);

        $data = User::where('username','=', $request->username)->first();
        return response()->json([
			'message' => 'Berhasil menambah user',
			'data' => $data
		]);
	}

	public function loginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return response()->json(['message' => 'Invalid Token']);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json(['message' => 'Token expired!']);
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json(['message' => 'Invalid Token!']);
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json(['message' => 'Token Absent']);
		}

		return response()->json(['message' => 'Success']);
	}
	
    public function logout(Request $request)
    {
		if(JWTAuth::invalidate(JWTAuth::getToken())) {
			return response()->json(['message' => 'You are logged out']);
        } else {
            return response()->json(['message' => 'Failed']);
        }
    }
}

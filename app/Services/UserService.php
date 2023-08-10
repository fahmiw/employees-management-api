<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserService 
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function create($data) {
            $dataValidate = Validator::make($data->all(), [
                "name" => "required",
                "email" => "required|email|unique:users",
                "password" => "required|required_with:password_confirmation|same:password_confirmation",
                'password_confirmation' => 'required'
            ]);

            if($dataValidate->fails()){
                return response()->json([
                    "statusCode" => 400,
                    "message" => "The given data was invalid.",
                    "errors" => $dataValidate->errors()
                ], 400);
            }
    
            $user = $this->userRepository->createUser([
                'name' => $data->name,
                'email' => $data->email,
                'password' => bcrypt($data->password),
            ]);
    
            return response()->json([
                "statusCode" => 201,
                "message" => "User registered successfully",
                "data" => $user
            ], 201);
    }

    public function verify($data) {
        try{
            $dataValidate = Validator::make($data->all(), [
                "email" => "required|email",
                "password" => "required"
            ]);

            if($dataValidate->fails()){
                return response()->json([
                    "statusCode" => 400,
                    "message" => "The given data was invalid.",
                    "errors" => $dataValidate->errors()
                ], 400);
            }

            if (!$token = auth()->attempt(["email" => $data->email, "password" => $data->password])) {
            return response()->json([
                    "statusCode" => 400,
                    "message" => "Invalid credentials"
                ], 200);
            }

            return response()->json([
                "statusCode" => 200,
                "message" => "Logged in successfully",
                "data" => [
                    "access_token" => $token
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy() {
        try{
            auth()->logout();
            return response()->json([
                "statusCode" => 200,
                "message" => "User logged out"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "statusCode" => 400,
                "message" => $e->getMessage(),
            ], 400);
        }
    }
}
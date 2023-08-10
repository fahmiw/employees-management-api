<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{   
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(Request $request) {
        return $this->userService->create($request);
    }

    public function login(Request $request) {   
        return $this->userService->verify($request);
    }
    
    public function logout() {
        return $this->userService->destroy();
    }
}

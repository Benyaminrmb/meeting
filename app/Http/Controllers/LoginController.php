<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        $login_credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (auth()->attempt($login_credentials)) {
            //generate the token for the user
            $user = auth()->user();
            $user->access_token = $user->createToken('token')->accessToken;
            //now return this token on success login attempt
            return $this->successResponse(UserResource::make($user));
        }
        return $this->failedResponse(['email' => ['The email or password is wrong.']]);

    }

    /**
     * for /auth/user
     * @return JsonResponse
     */
    public function currentUser(): JsonResponse
    {
        $user = Auth::user();
        return $this->successResponse(new UserResource($user));
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user()->token();
        $user->revoke();


        return $this->successResponse(true);

    }
}

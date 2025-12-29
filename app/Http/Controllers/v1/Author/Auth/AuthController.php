<?php

namespace App\Http\Controllers\v1\Author\Auth;

use App\Http\Controllers\Controller;
 use App\Http\Requests\v1\Author\Auth\LoginRequest;
use App\Http\Requests\v1\Author\Auth\RegisterAuthorRequest;
use App\Http\Requests\v1\Author\Auth\RegisterRequest;
use App\Http\Requests\v1\Author\Auth\RegisterStepTwoRequest;
use App\Http\Requests\v1\Author\Auth\ResetPasswordRequest;
use App\Http\Requests\v1\Author\Auth\SendOTPRequest;
use App\Http\Requests\v1\Author\Auth\SocialLoginRequest;
use App\Http\Requests\v1\Author\Auth\UpdatePasswordRequest;
use App\Http\Requests\v1\Author\Auth\ValidateOTPRequest;
use App\Http\Resources\v1\Author\Auth\RegisterResource;
use App\Http\Resources\v1\Author\Auth\RegisterStepTwoResource;
use App\Services\v1\Author\Auth\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        return $this->returnData(__("messages.user.loggedIn"),200,
            new RegisterResource($this->authService->login($request)));
    }
    
    public function register(RegisterRequest $request)
    {
        return $this->returnData(__("messages.user.registered"),200,
            new RegisterResource($this->authService->register($request)));
    }

    public function registerstep2(RegisterStepTwoRequest $request)
    {
        return $this->returnData(__("messages.user.registered"),200,
            new RegisterStepTwoResource($this->authService->registersteptwo($request)));
    }
    


    public function registerAuthor(RegisterAuthorRequest $request)
    {
        return $this->returnData(__("messages.author.registered"),200,
            new RegisterResource($this->authService->registerAuthor($request)));
    }
    public function changePassword(UpdatePasswordRequest $request)
    {
        $this->authService->changePassword($request);
        return $this->success(__("messages.user.change_password"),200);
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->success(__("messages.user.logout"),200);
    }
    public function sendOneTimePassword(SendOTPRequest $request)
    {
        return $this->authService->sendOneTimePassword($request);
    }


   /* public function validateOTP(ValidateOTPRequest $request)
    {
        $user = $this->authService->validateOneTimePassword($request, auth()->user());

        if ($user) {
            return $this->success(__("messages.otp_verification"), 200);
        }

        return $this->success(__("messages.invalid_otp"), 422);
    }*/



    public function validateOTP(ValidateOTPRequest $request){
        $this->authService->validateOneTimePassword($request);
        return $this->success(__("messages.otp_verification"), 200,);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->authService->resetOneTimePassword($request);
        return $this->success(
            __('messages.updated_successfully'),
            200
        );
    }
    public function loginWithGoogle(SocialLoginRequest $request)
    {
        return $this->returnData(__("messages.login_with_social"),201,
            $this->authService->loginWithGoogle($request));
    }

    public function checkFCM(SocialLoginRequest $request)
    {
        $msg = $this->authService->checkFCM($request);
        if ($msg)
            return $this->success(__("true"), 200);
        return $this->success(__("false"), 200);
    }


}
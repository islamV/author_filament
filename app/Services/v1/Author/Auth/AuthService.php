<?php

namespace App\Services\v1\Author\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use App\Models\InvitationCode;
use App\Traits\UtilitiesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\v1\Interface\Author\Auth\IUser;
use App\Http\Resources\v1\Author\Auth\RegisterResource;

class AuthService
{
    use ResponseTrait, UtilitiesTrait;

    protected IUser $user;

    public function __construct(IUser $user)
    {
        $this->user = $user;
    }

    public function login($request)
    {
        $user = $this->user->getByEmail($request->email);

        if ($user && Hash::check($request->password, $user->password)) {

          //  if (is_null($user->email_verified_at)) {
          //      return $this->returnError("البريد الإلكتروني لم يتم تفعيله", 403);
           // }
            $token = $user->createToken($user->password)->plainTextToken;
            $user->token = $token;
            return $user;
        } else
            return $this->returnError(__("messages.user.bad_credentials"), 422);
    }

    public function register($request)
    {
        // Step 1: Create user without password (password will be set in step 2)
        // Generate temporary password that user must change in step 2
        $temporaryPassword = Hash::make(uniqid('temp_', true));

        $roleId = $request->role_id ?? 4;

        $settings = Setting::first();
        $status = $settings->new_writers_auto_active ? 'active' : 'pending';
        
        $user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'device_token' => $request->device_token,
            'password' => $temporaryPassword, // Temporary password, will be updated in step 2
            'status' =>  $status,
            'role_id' => $request->role_id ?? 4, // Default to 4 (Reader) if not provided
            'work_link'    => $roleId == 4 ? null : $request->work_link,
            'description'  => $roleId == 4 ? null : $request->description,
        ];
        
        $user = $this->user->store($user);
        
        // Return user without token (token will be generated in step 2)
        return $user;
    }


    public function registersteptwo($request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return $this->returnError(__('messages.user_not_found'), 404);
        }

        // Only allow Author (role_id = 3) and Reader (role_id = 4) for step 2
        if (!in_array((int) $user->role_id, [3, 4])) {
            return $this->returnError(__('messages.roles.allowed_roles_only'), 403);
        }

        // Update user with password and country
        $user->password = Hash::make($request->password);
        $user->country_id = $request->country_id;
        $user->save();
        

        // Handle invitation code if provided
        if ($request->filled('invitation_code')) {
            $codeOwner = User::where('invitation_code', $request->invitation_code)->first();

            if (!$codeOwner) {
                return $this->returnError(__('messages.invitation_code.invalid'), 422);
            }

            if ($codeOwner->id == $user->id) {
                return $this->returnError(__('messages.invitation_code.cannot_use_own'), 422);
            }

        //    $alreadyUsed = InvitationCode::where('invitation_code', $request->invitation_code)
        //        ->where('used_by', $user->id)
        //        ->exists();

            $alreadyUsed = InvitationCode::where('invitation_code', $request->invitation_code)
                ->where(function ($q) use ($user) {
                    $q->where('used_by', $user->id)
                    ->orWhere('device_token', $user->device_token);
                })
            ->exists();

            
            if ($alreadyUsed) {
                return $this->returnError(__('messages.invitation_code.already_used_by_user'), 422);
            }

            InvitationCode::create([
                'user_id' => $codeOwner->id, 
                'invitation_code' => $request->invitation_code,
                'used_by' => $user->id, 
                'device_token' => $user->device_token, 
            ]);
            
        
            $settings = Setting::first();
            $rewardPoints = $settings->invitation_reward_points ?? 0;

            if ($rewardPoints > 0) {
                $codeOwner->increment('invitation_points', $rewardPoints);
            }

            $user->is_trial_user = true; 
            $user->trial_expires_at = now()->addDays(15);
            $user->save();
        }

        // Generate token and return user
        $token = $user->createToken($user->password)->plainTextToken;
        $user->token = $token;
        
        return $user;
    }


    public function registerAuthor($request)
    {
        $settings = Setting::first();
        $status = $settings->new_writers_auto_active ? 'active' : 'pending';

        $user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'work_link' => $request->work_link,
            'description' => $request->description,
            'device_token' => $request->device_token,
            'password' => Hash::make($request->password),
            'status' =>  $status,
            'role_id' => 3,
        ];
        $user = $this->user->store($user);
        
        if (in_array($user->role_id, [2, 3])) {
            \App\Models\Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }
        $token = $user->createToken($user->password)->plainTextToken;
        $user->token = $token;
        return $user;
    }

    public function changePassword($request)
    {
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $this->user->update($user);
        } else
            return $this->returnError(__("messages.user.bad_credentials"), 422);
    }

    public function logout()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
        }
    }

    public function sendOneTimePassword($request)
    {
        $user = $this->user->getByEmail($request->email);
        if (!$user)
            $this->returnError(__("errors.user_not_found"), 404);
        $this->sendOTP($user, $this->user);
        return $this->success(__('auth.otp_send'), 200);
    }

   /* public function validateOneTimePassword($request)
    {
        
        $user = $this->validateOTP($request, $this->user);

        if ($user && is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        return $user;
    }*/


    public function validateOneTimePassword($request)
    {
        $user = $this->user->getByEmail($request->email);

        if (!$user) {
            return null; // user not found
        }

        // Check if OTP is still valid
        if (Carbon::now()->gt($user->otp_valid_until)) {
            return null; // OTP expired
        }

        // Compare OTP safely
        if (Hash::check($request->otp, $user->otp)) {
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->save();
            }

            // Optionally clear OTP after successful verification
            $user->otp = null;
            $user->otp_valid_until = null;
            $user->save();

            return $user;
        }

        return null; // OTP invalid
    }


    public function resetOneTimePassword($request)
    {
        $this->resetPassword($request, $this->user);
    }

    public function loginWithGoogle($request)
    {
        $response = (object)[];
        $token = $request->bearerToken();
        $response->token = $token;
        $user = json_decode(
            base64_decode(
                str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1])
                )
            )
        );
        $existUser = $this->user->getByEmail($user->email);
        if ($existUser) {
            $existUser->token = $existUser->createToken("social_token")->plainTextToken;
            return new RegisterResource($existUser);
        }
        if (!$user->email_verified)
            $this->returnError(__('messages.email.not_verified'), 403);

        if ($request->role_id == null)
            $this->returnError(__('messages.roles.required'), 500);
        if ($request->role_id == 3) {
            $authUser = [
                'first_name' => $user->name,
                'last_name' => $user->name,
                'email' => $user->email,
                'work_link' => $request->work_link,
                'description' => $request->description,
                'device_token' => $request->device_token,
                'password' => Hash::make('XIKGHkdskj0@21Skdnk'),
                'email_verified_at' => $user->email_verified ? now() : null,
                'role_id' => 3,
            ];
        } else {
            $authUser = [
                'first_name' => $user->name,
                'last_name' => $user->name,
                'email' => $user->email,
                'status' => 'pending',
                'device_token' => $request->device_token,
                'password' => Hash::make('XIKGHkdskj0@21Skdnk'),
                'email_verified_at' => $user->email_verified ? now() : null,
                'role_id' => 4,
            ];
        }
        $storedUser = $this->user->store($authUser);
        $storedUser->token = $storedUser->createToken("social_token")->plainTextToken;
        return new RegisterResource($storedUser);
    }

    public function checkFCM($request)
    {
        $response = (object)[];
        $token = $request->bearerToken();
        $response->token = $token;
        $user = json_decode(
            base64_decode(
                str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1])
                )
            )
        );
        $existUser = $this->user->getByEmail($user->email);
        if ($existUser)
            return true;
        return false;

    }


}

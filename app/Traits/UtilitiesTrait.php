<?php

namespace App\Traits;


use App\Mail\OTPMail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

trait UtilitiesTrait
{
    use ResponseTrait;

    /**
     * @throws Exception
     */


    public function sendOTP($user, $IName)
    {
        try {
            $otp = mt_rand(100000, 999999);
            $user->otp = Hash::make($otp);
            $user->otp_valid_until = Carbon::now()->addMinutes(10);
            $IName->update($user);
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function validateOTP($request, $IName)
    {
        $pin = $request->otp;
        $user = $IName->getByEmail($request->email);
        if (!$user)
            $this->returnError(__("messages.user_not_found"), 404);

        if (Hash::check($pin, $user->otp) && $user->otp_valid_until > now()) {
            $user->otp = null;
            $user->otp_valid_until = null;
            $IName->update($user);
        } else
            $this->returnError(__("messages.token.invalid_token"), 404);

    }

    public function resetPassword($request, $IName)
    {
        if ($request->password === $request->password_confirmation) {
            $user = $IName->getByEmail($request->email);
            $user->password = Hash::make($request->password);
            $IName->update($user);
        } else
            $this->returnError(__("messages.password.don't_match"), 422);
    }

   /* public function resetPassword($request, $IName)
    {
        $user = $IName->getByEmail($request->email);

        if (!$user) {
            return $this->returnError(__('messages.user_not_found'), 404);
        }

        if (
            !$user->otp ||
            Carbon::now()->gt($user->otp_valid_until) ||
            !Hash::check($request->otp, $user->otp)
        ) {
            return $this->returnError(__('messages.invalid_otp'), 422);
        }

        if ($request->password !== $request->password_confirmation) {
            return $this->returnError(__('messages.password.dont_match'), 422);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_valid_until = null;

        $IName->update($user);
    }*/

}

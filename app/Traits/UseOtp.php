<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\VerifyOtpNotification;
use Tzsk\Otp\Facades\Otp;

trait UseOtp
{
    public function sendVericationEmail(User $user)
    {
        $secret = $this->getSecret($user);

        $otp = Otp::generate($secret);

        $user->notify(new VerifyOtpNotification($user->name, $otp));
    }

    public function isValidOtp(User $user, $otp): bool
    {
        return Otp::check($otp, $this->getSecret($user));
    }

    private function getSecret(User $user)
    {
        return md5($user->email);
    }

    public function resend(User $user)
    {
        $this->sendVericationEmail($user);
    }
}

<?php

namespace Mkdev\LaravelAdvancedOTP;


use Mkdev\LaravelAdvancedOTP\Contracts\OTPProviderContract;
use Mkdev\LaravelAdvancedOTP\Enums\OTPStatusEnum;
use Mkdev\LaravelAdvancedOTP\Exceptions\InvalidOTPMethodException;
use Mkdev\LaravelAdvancedOTP\Interfaces\OTPProviderInterface;

class LaravelAdvancedOTP
{

    private function getMethod(string $method)
    {
        $otpMethod = new $method();
        if (!$otpMethod instanceof OTPProviderContract) {
            throw new InvalidOTPMethodException;
        }
        return $otpMethod;
    }

    public function handle(string $method, array $signature = []): OTPProviderInterface
    {
        return $this->getMethod($method)->handle($signature);
    }

    public function validate($method, $otp, ...$parameters)
    {
        return $this->getMethod($method)->validate($otp, ...$parameters);
    }

    public function verify($method, $otp, $signature = [], $token): OTPStatusEnum
    {
        return $this->getMethod($method)->verify($otp, $signature, $token);
    }
}

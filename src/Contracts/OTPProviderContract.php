<?php

namespace Mkdev\LaravelAdvancedOTP\Contracts;

use Mkdev\LaravelAdvancedOTP\Enums\OTPStatusEnum;
use Mkdev\LaravelAdvancedOTP\Enums\OTPTypeEnums;
use Mkdev\LaravelAdvancedOTP\Interfaces\OTPProviderInterface;

class OTPProviderContract implements OTPProviderInterface
{
    protected int $timeout = 30;
    protected OTPTypeEnums $type = OTPTypeEnums::Number;


    public function getHashedKey(): string
    {
        // TODO: Implement getHashedKey() method.
    }

    public function handle(array $signature = []): OTPProviderInterface
    {
        // TODO: Implement handle() method.
    }

    public function verify(int|string $otp, array $signature = [], string $hashedToken = null): OTPStatusEnum
    {
        // TODO: Implement verify() method.
    }

    private function generateOTP(int $length = 8): string|int
    {
        // TODO: Implement generateOTP() method.
    }

    public function getOTP(): string|int
    {
        // TODO: Implement getOTP() method.
    }
}

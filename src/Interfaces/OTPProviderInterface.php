<?php

namespace Mkdev\LaravelAdvancedOTP\Interfaces;

use Mkdev\LaravelAdvancedOTP\Enums\OTPStatusEnum;

interface OTPProviderInterface
{

    public function handle(array $signature = []): self;

    public function verify(string|int $otp, array $signature = [], string $hashedToken = null): OTPStatusEnum;

    public function getOTP(): string|int;

    public function getHashedKey(): string;
 }

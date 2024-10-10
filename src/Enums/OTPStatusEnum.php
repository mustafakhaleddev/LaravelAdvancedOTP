<?php

namespace Mkdev\LaravelAdvancedOTP\Enums;

enum OTPStatusEnum
{
    case VERIFIED;
    case NOT_VERIFIED;
    case EXPIRED;
}

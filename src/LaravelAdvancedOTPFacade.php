<?php

namespace Mkdev\LaravelAdvancedOTP;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mkdev\LaravelAdvancedOTP\Skeleton\SkeletonClass
 */
class LaravelAdvancedOTPFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-advanced-otp';
    }
}

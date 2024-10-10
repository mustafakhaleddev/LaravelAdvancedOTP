# Laravel Advanced OTP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mkd/laravel-advanced-otp.svg?style=flat-square)](https://packagist.org/packages/mkdev/laravel-advanced-otp)
[![Total Downloads](https://img.shields.io/packagist/dt/mkd/laravel-advanced-otp.svg?style=flat-square)](https://packagist.org/packages/mkdev/laravel-advanced-otp)
![GitHub Actions](https://github.com/mustafakhaleddev/LaravelAdvancedOTP/actions/workflows/main.yml/badge.svg)

Laravel Advanced OTP is a package designed for flexible OTP (One-Time Password) verification, supporting both hashed token verification and custom validation methods. It allows for easy OTP handling for tasks like email-based authentication.

---

## Features
- **Hashed Token Verification**: Secure OTP validation using hashed tokens.
- **Custom Validation**: Developers can use their own validation methods (e.g., database or cache-based).
- **Configurable OTP Settings**: Custom timeout and OTP length.

## Installation

Install the package via Composer:

```bash
composer require mkd/laravel-advanced-otp
```


Create your own OTPMethod

```bash
php artisan magic-otp:make LoginOTP
```

## Usage

### 1. OTP Generation and Email Sending (Hashed Token)

In this example, a hashed token is used to securely send and verify the OTP.

```php
// Generate OTP and send it via email
$otp = \LaravelAdvancedOTP::handle(LoginOTP::class, [
    'secret' => 'secret_key',  // Required to hash and verify OTP
    'email' => 'user_email@example.com',  // Email of the recipient
]);

// Get the hashed token for verification
$token = $otp->getHashedKey();

// Send OTP to user's email
$otp->send('user_email@example.com');

// Return the hashed token for later verification
return response()->json(['token' => $token]);
```

### 2. OTP Generation Without Hashed Token

If you want to handle the OTP validation manually (e.g., store it in a database or cache), you can omit the hashed token.

```php
// Generate and send OTP without hashed token
\LaravelAdvancedOTP::handle(LoginOTP::class)->send('user_email@example.com');
```

### 3. Verifying OTP (Hashed Token)

Use the hashed token to validate the OTP.

```php
$otp = request('otp');
$hashedToken = request('token');  // Token returned when sending OTP

$signature = [
    'secret' => 'secret_key',  // Same secret used during OTP generation
    'email' => 'user_email@example.com',
];

// Verify the OTP using the hashed token
$otpStatus = \LaravelAdvancedOTP::verify(LoginOTP::class, $otp, $signature, $hashedToken);

if ($otpStatus == OTPStatusEnum::NOT_VERIFIED) {
    // OTP is invalid
}

if ($otpStatus == OTPStatusEnum::VERIFIED) {
    // OTP is valid
}

if ($otpStatus == OTPStatusEnum::EXPIRED) {
    // OTP has expired
}
```

### 4. Verifying OTP (Custom Validation)

If you want to handle OTP validation manually, you can use your custom logic for verification.

```php
$otp = request('otp');
$email = request('email');

// Custom validation for OTP
$otpVerified = \LaravelAdvancedOTP::validate(LoginOTP::class, $otp, $email);

if ($otpVerified) {
    // OTP is valid
} else {
    // OTP is invalid or expired
}
```

## Custom OTP Class

To implement your OTP logic, create a class extending `MagicOTP`. Here is an example:

```php
class LoginOTP extends MagicOTP
{
    protected int $timeout = 120;  // Timeout in seconds
    protected int $otpLength = 5;  // Length of the OTP

    public function send($email)
    {
        $otp = $this->getOTP();
        // Logic to send OTP via email
    }

    public function validate($otp, $email)
    {
        // Logic to validate OTP for the email
    }
}
```

## Configuration

You can adjust the default settings like OTP timeout, length, and more by customizing your OTP class.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mustafakhaleddev@gmail.com instead of using the issue tracker.

## Credits

-   [Mustafa Khaled](https://github.com/mkdev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).

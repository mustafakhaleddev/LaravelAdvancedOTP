<?php

namespace Mkdev\LaravelAdvancedOTP;


use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Mkdev\LaravelAdvancedOTP\Contracts\OTPProviderContract;
use Mkdev\LaravelAdvancedOTP\Enums\OTPStatusEnum;
use Mkdev\LaravelAdvancedOTP\Enums\OTPTypeEnums;

class MagicOTP extends OTPProviderContract
{
    protected int $timeout = 90; // Timeout in seconds
    private string $hashedData;
    private string $hashedKey;
    private string $generatedOTP;

    protected OTPTypeEnums $type = OTPTypeEnums::Number;

    protected int $otpLength = 6;


    final public function handle(array $signature = []): self
    {
        // Generate an OTP
        $this->generatedOTP = $this->generateOTP($this->otpLength); // Customize length/type as needed
        // Combine the OTP with the data string
        $this->hashedData = $this->getHashedData($signature);

        // Store the current timestamp along with the hashed OTP
        $otpData = [
            'hashed_otp' => $this->hashedData,
            'timestamp' => now(), // Store the current timestamp
        ];

        $this->hashedKey = Crypt::encrypt($otpData);
        return $this;
    }

    private function getHashedData(array $data = []): string
    {
        // Prepare data for hashing
        $dataString = $this->prepareDataString($data, $this->generatedOTP); // Convert data to string format
        // Combine the OTP with the data string
        return Hash::make($dataString);
    }

    // Helper function to convert data array to string
    private function prepareDataString(array $data = [], string|int $otp = null): string
    {
        $dataStrings = [];
        foreach ($data as $key => $value) {
            $dataStrings[] = $key . '|' . $value; // Create key|value format
        }
        return implode('...', $dataStrings) . '|' . $otp; // Join with '...' as specified
    }

    final public function verify(string|int $otp, array $signature = [], string $hashedToken = null): OTPStatusEnum
    {
        // Decrypt the token to retrieve hashed OTP and timestamp
        $otpData = Crypt::decrypt($hashedToken);

        // Check if the OTP has expired
        if ($this->isExpired($otpData['timestamp'])) {
            return OTPStatusEnum::EXPIRED; // OTP is invalid due to timeout
        }

        $dataToCheck = $this->prepareDataString($signature, $otp);

        // Verify the hashed input against the stored hash
        return Hash::check($dataToCheck, $otpData['hashed_otp']) ? OTPStatusEnum::VERIFIED : OTPStatusEnum::NOT_VERIFIED;
    }

    private function isExpired(Carbon $timestamp): bool
    {
        // Calculate the difference in minutes
        $now = now();
        $differenceInSeconds = $now->diffInSeconds($timestamp) * -1;
        // Check if the difference exceeds the timeout
        return $differenceInSeconds > $this->timeout;
    }

    private function generateOTP(int $length = 8): string|int
    {
        switch ($this->type) {
            case OTPTypeEnums::Number:
                // Generate a numeric OTP of the specified length
                $otp = '';
                for ($i = 0; $i < $length; $i++) {
                    $otp .= mt_rand(0, 9); // Append random digits
                }
                return $otp;

            case OTPTypeEnums::Alphanumeric:
                // Generate an alphanumeric OTP
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $otp = '';
                for ($i = 0; $i < $length; $i++) {
                    $otp .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
                return $otp;

            case OTPTypeEnums::Alphabet:
                // Generate an alphabet-only OTP
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $otp = '';
                for ($i = 0; $i < $length; $i++) {
                    $otp .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
                return $otp;

            default:
                throw new \InvalidArgumentException('Invalid OTP type provided.');
        }
    }

    public function getOTP(): string|int
    {
        return $this->generatedOTP;
    }

    public function getHashedKey(): string
    {
        return $this->hashedKey;
    }


}

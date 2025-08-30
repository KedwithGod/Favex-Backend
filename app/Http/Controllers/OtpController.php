<?php

namespace App\Http\Controllers;

use App\Helpers\ConstantHandler;
use App\Helpers\ErrorHandler;
use App\Helpers\Success;
use App\Http\Requests\Otp\SendOtpRequest;
use App\Http\Requests\Otp\VerifyOtpRequest;
use App\Http\Requests\Otp\ResetPasswordRequest;
use App\Http\Requests\OTP\UnauthenticatedSendOTPRequest;
use App\Http\Requests\OTP\UnauthenticatedVerifyOTPRequest;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    protected function createOtp(User $user, string $purpose): Otp
    {
        // 6-digit numeric
        $raw = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = Otp::create([
            'user_id'   => $user->id,
            'purpose'   => $purpose,
            'code_hash' => Hash::make($raw),
            'expires_at' => now()->addMinutes(ConstantHandler::$otpDuration),
        ]);

        // "Send" via email -> logs for dev/demo
        Log::info("OTP for {$user->email} (purpose: {$purpose}): {$raw}");

        return $otp;
    }

    public function send(SendOtpRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();

            // Invalidate previous unconsumed OTPs for same purpose
            Otp::where('user_id', $user->id)
                ->where('purpose', $data['purpose'])
                ->whereNull('consumed_at')
                ->delete();

            $this->createOtp($user, $data['purpose']);

            return response()->json((new Success([
                'message' => 'OTP sent (check logs during development)',
            ]))->toArray());
        });
    }

    protected function createOtpForEmail(string $email, string $purpose): Otp
    {
        $code = rand(100000, 999999);

        $otp = Otp::create([
            'email'      => $email,
             'user_id'   => null ,
            'purpose'    => $purpose,
            'code_hash'  => bcrypt($code),
            'expires_at' => now()->addMinutes(ConstantHandler::$otpDuration),
        ]);

        // TODO: Send code via mail or SMS
        Log::info("Guest OTP for {$email}: {$code}");

        return $otp;
    }

    public function sendToGuest(UnauthenticatedSendOTPRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();

            // Invalidate previous unconsumed OTPs for this email + purpose
            Otp::where('email', $data['email'])
                ->where('purpose', $data['purpose'])
                ->whereNull('consumed_at')
                ->delete();

            // Create new OTP
            $this->createOtpForEmail($data['email'], $data['purpose']);

            return response()->json((new Success([
                'message' => 'OTP sent to guest email (check logs during development)',
            ]))->toArray());
        });
    }


    public function verify(VerifyOtpRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();

            $otp = Otp::where('user_id', $user->id)
                ->where('purpose', $data['purpose'])
                ->latest()
                ->first();

            if (!$otp || $otp->isExpired() || $otp->isUsed() || !Hash::check($data['otp'], $otp->code_hash)) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'invalid_otp',
                        'message' => 'Invalid or expired OTP',
                        'code' => 401,
                    ],
                ], 422);
            }

            $otp->update(['consumed_at' => now()]);

            return response()->json((new Success([
                'message' => 'OTP verified',
            ]))->toArray());
        });
    }

    public function verifyGuest(UnauthenticatedVerifyOTPRequest $request)
{
    return ErrorHandler::callWithErrorHandling(function () use ($request) {
        $data = $request->validated();
        INFO(json_encode($data));

        $otp = Otp::where('email', $data['email'])
            ->where('purpose', $data['purpose'])
            ->latest()
            ->first();
        
        info($otp);

        if (
            !$otp ||
            $otp->isExpired() ||
            $otp->isUsed() ||
            !Hash::check($data['otp'], $otp->code_hash)
        ) {
            return response()->json([
                'success' => false,
                'error' => [
                    'type' => 'invalid_otp',
                    'message' => 'Invalid or expired OTP',
                    'code' => 422,
                ],
            ], 422);
        }

        $otp->update(['consumed_at' => now()]);

        return response()->json((new Success([
            'message' => 'Guest OTP verified',
        ]))->toArray());
    });
}


    public function resetPassword(ResetPasswordRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();

            $user->password = Hash::make($data['password']);
            $user->save();

            return response()->json((new Success([
                'message' => 'Password reset successful',
            ]))->toArray());
        });
    }
}

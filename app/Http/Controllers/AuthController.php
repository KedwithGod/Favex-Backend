<?php
namespace App\Http\Controllers;

use App\Helpers\ErrorHandler;
use App\Helpers\Success;
use App\Http\Requests\Auth\RegisterBiometricRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\LoginBiometricRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UnlockRequest;
use App\Http\Requests\Auth\SetPinRequest;
use App\Http\Requests\UniqueEmailRequest;
use App\Http\Requests\UniquePhoneRequest;
use App\Http\Requests\UniqueUsernameRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
public function setTransactionPin(SetPinRequest $request)
{
    return ErrorHandler::callWithErrorHandling(function () use ($request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        info($data['email']);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'type' => 'invalid_user',
                    'message' => 'No user found with email ' . $data['email'],
                    'code' => 404,
                ],
            ], 404);
        }

        $user->txn_pin_hash = Hash::make($data['txn_pin']);
        $user->save();

        return response()->json((new Success([
            'message' => 'Transaction PIN set successfully'
        ]))->toArray());
    });
}


    public function register(RegisterUserRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();

            $user = User::create([
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'username'     => $data['username'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'where_heard'  => $data['where_heard'] ?? null,
                'referral_tag' => $data['referral_tag'] ?? null,
                'password'     => Hash::make($data['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json((new Success([
                'message' => 'User created',
                'user'    => $user->only(['id','first_name','last_name','username','email','phone']),
                'token'   => $token,
            ]))->toArray(), 201);
        });
    }

    public function login(LoginRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json((new \App\Helpers\Error(
                    'invalid_credentials', 'Invalid credentials', 422
                ))->toArray(), 422);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json((new Success([
                'user'=>$user,
                'message' => 'Login OK',
                'biometric_enabled' => !is_null($user->biometric_token),
                'token' => $token,
            ]))->toArray());
        });
    }

    public function unlock(UnlockRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $user = User::where('email', $request->validated()['email'])->first();

            if (!$user || !Hash::check($request->txn_pin, $user->txn_pin_hash)) {
                return response()->json((new \App\Helpers\Error(
                    'invalid_pin', 'Invalid PIN', 422
                ))->toArray(), 422);
            }

            return response()->json((new Success([
                'message' => 'Phone Unlocked'
            ]))->toArray());
        });
    }

    public function registerBiometric(RegisterBiometricRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();
            $token = $user->createToken('biometric_token')->plainTextToken;

            $user->biometric_token = $token;
            $user->save();

            return response()->json((new Success([
                'message' => 'Biometric registered',
            ]))->toArray());
        });
    }

    public function loginBiometric(LoginBiometricRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();

            if (is_null($user->biometric_token)) {
                return response()->json((new \App\Helpers\Error(
                    'biometric_missing', 'Biometric not set for this user', 422
                ))->toArray(), 422);
            }

            if ($user->biometric_token !== $data['biometric_token']) {
                return response()->json((new \App\Helpers\Error(
                    'invalid_biometric', 'Invalid biometric token', 422
                ))->toArray(), 422);
            }

            return response()->json((new Success([
                'message' => 'Biometric Login OK',
            ]))->toArray());
        });
    }

    public function uniqueEmail(UniqueEmailRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $value = $request->email;
          
            $exists = User::where('email', $value)->exists();
             
            if($exists){
             return response()->json((new \App\Helpers\Error(
                    'invalid_email', 'Email already exist in our system', 422
                ))->toArray(), 422);
            }

            return response()->json((new Success([
                'unique' => !$exists
            ]))->toArray());
        });
    }

    public function uniqueUsername(UniqueUsernameRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $value = $request->username;
              info($value);
            $exists = User::where('username', $value)->exists();

              if($exists){
             return response()->json((new \App\Helpers\Error(
                    'invalid_username', 'Username already exist in our system', 422
                ))->toArray(), 422);
            }

            return response()->json((new Success([
                'unique' => !$exists
            ]))->toArray());
        });
    }

    public function uniquePhone(UniquePhoneRequest $request)
    {
        return ErrorHandler::callWithErrorHandling(function () use ($request) {
            $value = $request->phone;
            
            $exists = User::where('phone', $value)->exists();

              if($exists){
             return response()->json((new \App\Helpers\Error(
                    'invalid_phone_number', 'Phone number already exist in our system', 422
                ))->toArray(), 422);
            }

            return response()->json((new Success([
                'unique' => !$exists
            ]))->toArray());
        });
    }
}

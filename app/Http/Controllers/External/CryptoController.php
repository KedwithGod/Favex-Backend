<?php

namespace App\Http\Controllers\External;

use Illuminate\Support\Facades\Http;
use App\Helpers\ErrorHandler;
use App\Helpers\Success;
use App\Http\Controllers\Controller;
class CryptoController extends Controller
{
       public function getAllCryptoData()
    {
        return ErrorHandler::callWithErrorHandling(function () {
            $baseUrl = config(key: 'services.STAGING_URL');
         
            $url = $baseUrl . '/api/staging/crypto/all';

            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'external_api_error',
                        'message' => 'Failed to fetch crytpo from staging service',
                        'details' => $response->json(),
                        'code' => $response->status(),
                    ],
                ], $response->status());
            }

            $data = $response->json();

            return response()->json(
                (new Success([
                    'message' => 'Crytpo Data fetched successfully',
                    'data'    => $data,
                ]))->toArray()
            );
        });
    }
}

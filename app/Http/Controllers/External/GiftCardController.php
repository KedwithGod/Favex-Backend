<?php

namespace App\Http\Controllers\External;

use Illuminate\Support\Facades\Http;
use App\Helpers\ErrorHandler;
use App\Helpers\Success;
use App\Http\Controllers\Controller;

class GiftCardController extends Controller
{
    public function getAllGiftcards()
    {
        return ErrorHandler::callWithErrorHandling(function () {
            $baseUrl = config(key: 'services.STAGING_URL');
         
            $url = $baseUrl . '/api/staging/giftcard/all';

            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'external_api_error',
                        'message' => 'Failed to fetch giftcards from staging service',
                        'details' => $response->json(),
                        'code' => $response->status(),
                    ],
                ], $response->status());
            }

            $data = $response->json();

            return response()->json(
                (new Success([
                    'message' => 'Giftcards fetched successfully',
                    'data'    => $data,
                ]))->toArray()
            );
        });
    }
}

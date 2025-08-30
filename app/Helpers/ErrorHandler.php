<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\Client\RequestException;
use TypeError;

class ErrorHandler
{
    public static function callWithErrorHandling(callable $callback)
    {
        try {
            // Set timeout for HTTP requests if the callback involves external calls
            return  $callback();
        } catch (RequestException $e) {
            return response()->json(
                (new Error('network_timeout', 'Network timeout: ' . $e->getMessage(), 504))->toArray(),
                504
            );
        } catch (TypeError $e) {
            return response()->json(
                (new Error('type_error', 'Type error: ' . $e->getMessage(), 500))->toArray(),
                500
            );
        } catch (Exception $e) {
            return response()->json(
                (new Error('general_error', 'Unexpected error: ' . $e->getMessage(), 500))->toArray(),
                500
            );
        }
    }
}

class Success
{
    public function __construct(public array $data) {}

    public function toArray()
    {
        return [
            'success' => true,
            'data' => $this->data,
        ];
    }
}

class Error
{
    public function __construct(public string $type, public string $message, public int $code) {}

    public function toArray()
    {
        return [
            'success' => false,
            'error' => [
                'type' => $this->type,
                'message' => $this->message,
                'code' => $this->code,
            ],
        ];
    }
}

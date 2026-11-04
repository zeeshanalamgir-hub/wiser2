<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class EscrowService
{
    protected $client;
    protected $baseUrl;
    protected $credentials;

    public function __construct()
    {
        $this->baseUrl = config('services.escrow.url');
        $this->credentials = [
            config('services.escrow.email'),
            config('services.escrow.key')
        ];
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * Create a new customer in Escrow
     */
    public function createCustomer(array $customerData)
    {
        try {
            $response = $this->client->post('/2017-09-01/customer', [
                'auth' => $this->credentials,
                'json' => $customerData
            ]);

            $body = (string) $response->getBody(); // read once

            Log::info("Escrow API Response: " . $body);

            return [
                'success' => true,
                'data' => json_decode($body, true), // reuse same body
                'status' => $response->getStatusCode()
            ];
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorBody = $errorResponse ? json_decode((string) $errorResponse->getBody(), true) : null;

            Log::error('Escrow API Error: ' . $e->getMessage(), [
                'error' => $errorBody,
                'status' => $e->getCode()
            ]);

            return [
                'success' => false,
                'error' => $errorBody ?? $e->getMessage(),
                'status' => $e->getCode()
            ];
        }
    }
    /**
     * Create a new transaction in Escrow
     */

    public function createTransaction(array $transactionData)
    {
        try {
            $response = $this->client->post('/2017-09-01/transaction', [
                'auth' => $this->credentials,
                'json' => $transactionData
            ]);

            $body = (string) $response->getBody(); // read once

            Log::info("Escrow Transaction API Response: " . $body);

            return [
                'success' => true,
                'data' => json_decode($body, true), // reuse same body
                'status' => $response->getStatusCode()
            ];
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorBody = $errorResponse ? json_decode((string) $errorResponse->getBody(), true) : null;

            Log::error('Escrow Transaction API Error: ' . $e->getMessage(), [
                'error' => $errorBody,
                'status' => $e->getCode()
            ]);

            return [
                'success' => false,
                'error' => $errorBody ?? $e->getMessage(),
                'status' => $e->getCode()
            ];
        }
    }

    public function transactionDetails($id)
    {
        try {
            $response = $this->client->get("/2017-09-01/transaction/{$id}", [
                'auth' => $this->credentials,
            ]);

            $body = (string) $response->getBody(); // read once
            Log::info("Fetch Escrow Transaction Details API Response: " . $body);

            return [
                'success' => true,
                'data' => json_decode($body, true), // reuse same body
                'status' => $response->getStatusCode()
            ];
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorBody = $errorResponse ? json_decode((string) $errorResponse->getBody(), true) : null;

            Log::error('Fetch Escrow Transaction API Error: ' . $e->getMessage(), [
                'error' => $errorBody,
                'status' => $e->getCode()
            ]);

            return [
                'success' => false,
                'error' => $errorBody ?? $e->getMessage(),
                'status' => $e->getCode()
            ];
        }
    }

    public function fetchCustomer($id)
    {
        try {
            $response = $this->client->get("/2017-09-01/customer/{$id}", [
                'auth' => $this->credentials,
            ]);

            $body = (string) $response->getBody(); // read once
            Log::info("Fetch Escrow API Response: " . $body);

            return [
                'success' => true,
                'data' => json_decode($body, true), // reuse same body
                'status' => $response->getStatusCode()
            ];
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorBody = $errorResponse ? json_decode((string) $errorResponse->getBody(), true) : null;

            Log::error('Fetch Escrow API Error: ' . $e->getMessage(), [
                'error' => $errorBody,
                'status' => $e->getCode()
            ]);

            return [
                'success' => false,
                'error' => $errorBody ?? $e->getMessage(),
                'status' => $e->getCode()
            ];
        }
    }

}

<?php

namespace App\Http\Controllers\Gateway\Escrow;

use App\Http\Controllers\Controller;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class EscrowController extends Controller
{
    public function __construct(protected EscrowService $escrowService)
    {
        $this->escrowService = $escrowService;
    }

    /**
     * Create a new customer
     */
    public function createCustomer(Request $request)
    {
        // Validate the request if needed
        $validated = $request->validate([
            'phone_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            // 'middle_name' => 'required',
            'address.city' => 'required',
            'address.post_code' => 'required',
            'address.country' => 'required',
            // 'address.line2' => 'required',
            'address.line1' => 'required',
            'address.state' => 'required',
            'email' => 'required|email',
        ]);
        $result = $this->escrowService->createCustomer($validated);

        if ($result['success']) {
            return response()->json([
                'message' => 'Customer created successfully',
                'data' => $result['data']
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create customer',
            'error' => $result['error']
        ], $result['status'] ?? 500);
    }

    // Create Transction
    public function createTransaction(Request $request)
    {
        // Validate the request if needed
        $validated = $request->validate([
            'currency' => 'required',
            'items' => 'required|array',
        ]);
        $result = $this->escrowService->createTransaction($validated);

        if ($result['success']) {
            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $result['data']
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create customer',
            'error' => $result['error']
        ], $result['status'] ?? 500);
    }
    /**
     * Example endpoint using the hardcoded data
     */
    public function fetchCustomer($id)
    {
        $result = $this->escrowService->fetchCustomer($id);

        if ($result['success']) {
            return response()->json([
                'message' => 'Customer created successfully',
                'data' => $result['data']
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create customer',
            'error' => $result['error']
        ], $result['status'] ?? 500);
    }
}

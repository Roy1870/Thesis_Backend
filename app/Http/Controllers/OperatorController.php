<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Operator;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreOperatorRequest;

class OperatorController extends Controller
{
    /**
     * Display a listing of operators with related farmers.
     */
    public function index()
    {
        return response()->json(Operator::with('farmer')->get(), 200);
    }

    /**
     * Store a newly created operator along with farmer details.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Incoming Request Data:', $request->all()); // Log the request
    
            $validatedRequest = app(StoreOperatorRequest::class);
            $validated = $validatedRequest->validated();
    
            Log::info('Validated Data:', $validated); // Log validated data
    
            // Create or fetch the Farmer
            $farmer = isset($validated['farmer_id'])
                ? Farmer::find($validated['farmer_id'])
                : Farmer::create($validated);
    
            if (!$farmer) {
                return response()->json(['error' => 'Farmer not found.'], 404);
            }
    
            // Store Operator Records if provided
            if (!empty($validated['operators'])) {
                foreach ($validated['operators'] as $operatorData) {
                    Log::info('Storing Operator Record:', $operatorData);
                    $farmer->operators()->create($operatorData);
                }
            }
    
            return response()->json([
                'message' => 'Farmer and operator records saved successfully!',
                'data' => $farmer->load('operators'),
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error: ' . json_encode($e->errors()));
    
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('Error storing farmer and operator data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    /**
     * Display a specific operator.
     */
    public function show($id)
    {
        $operator = Operator::with('farmer')->find($id);

        if (!$operator) {
            return response()->json(['message' => 'Operator not found'], 404);
        }

        return response()->json($operator, 200);
    }

    /**
     * Update a specific operator.
     */
    public function update(StoreOperatorRequest $request, $id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['message' => 'Operator not found'], 404);
        }

        $operator->update($request->validated());

        return response()->json([
            'message' => 'Operator updated successfully!',
            'data' => $operator->load('farmer'),
        ], 200);
    }

    /**
     * Delete a specific operator.
     */
    public function destroy($id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['message' => 'Operator not found'], 404);
        }

        $operator->delete();

        return response()->json(['message' => 'Operator deleted successfully!'], 200);
    }
}

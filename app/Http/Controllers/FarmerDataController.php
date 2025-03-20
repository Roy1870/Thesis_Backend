<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Farmer;
use App\Http\Requests\FarmerDataRequest;

class FarmerDataController extends Controller
{
    protected $model = [FarmerDataRequest::class, Farmer::class];

    public function index()
    {
        return response()->json(Farmer::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            // Validate request dynamically
            $validatedRequest = app(FarmerDataRequest::class);
            $validated = $validatedRequest->validated();

            // Create new Farmer record
            $farmer = Farmer::create($validated);

            return response()->json([
                'message' => 'Farmer data saved successfully!',
                'data' => $farmer
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error storing farmer data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    public function show($id)
    {
        $farmer = Farmer::findOrFail($id);
        return response()->json($farmer, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate input
            $validatedRequest = app(FarmerDataRequest::class);
            $validated = array_filter($validatedRequest->validated(), fn($value) => !is_null($value));

            // Find and update record
            $farmer = Farmer::findOrFail($id);
            if (!empty($validated)) {
                $farmer->update($validated);
            }

            return response()->json($farmer, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating farmer data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            $farmer->delete();

            return response()->json(['message' => 'Farmer deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting farmer: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }
}

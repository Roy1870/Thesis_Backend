<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Farmer;
use App\Models\Crops;
use App\Models\Rice;
use App\Http\Requests\FarmerDataRequest;
use App\Http\Requests\CropsDataRequest;
use App\Http\Requests\RiceDataRequest;

class FarmerDataController extends Controller
{
    public function index(Request $request)
    {
        // Get pagination parameters from request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        // Create query builder
        $query = Farmer::with(['crops', 'rice']);
        
        // Add search functionality if search parameter is provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_number', 'LIKE', "%{$search}%");
            });
        }
        
        // Get paginated results
        $farmers = $query->paginate($perPage);
        
        return response()->json($farmers, 200);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Incoming Request Data:', $request->all()); // Log the request

            $validatedRequest = app(FarmerDataRequest::class);
            $validated = $validatedRequest->validated();

            Log::info('Validated Data:', $validated); // Log validated data

            // Create Farmer
            $farmer = Farmer::create($validated);

            // Store crops if provided
            if (!empty($request->crops)) {
                foreach ($request->crops as $cropData) {
                    Log::info('Storing Crop:', $cropData);
                    $farmer->crops()->create($cropData);
                }
            }

            // Store rice if provided
            if (!empty($request->rice)) {
                foreach ($request->rice as $riceData) {
                    Log::info('Storing Rice:', $riceData);
                    $farmer->rice()->create($riceData);
                }
            }

            return response()->json([
                'message' => 'Farmer data saved successfully!',
                'data' => $farmer->load(['crops', 'rice']),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error: ' . json_encode($e->errors()));

            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error storing farmer data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeCrops(CropsDataRequest $request, $farmerId)
    {
        try {
            $farmer = Farmer::findOrFail($farmerId);
    
            foreach ($request->crops as $cropData) {
                $farmer->crops()->create($cropData);
            }
    
            return response()->json([
                'message' => 'Crops stored successfully!',
                'data' => $farmer->load('crops'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function storeRice(RiceDataRequest $request, $farmerId)
    {
        try {
            $farmer = Farmer::findOrFail($farmerId);
    
            foreach ($request->rice as $riceData) {
                $farmer->rice()->create($riceData);
            }
    
            return response()->json([
                'message' => 'Rice stored successfully!',
                'data' => $farmer->load('rice'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    


    public function show($id)
    {
        $farmer = Farmer::with(['crops', 'rice'])->findOrFail($id);
        return response()->json($farmer, 200);
    }

    public function update(Request $request, $id)
{
    try {
        // Find the farmer
        $farmer = Farmer::findOrFail($id);

        // Validate input
        $validatedRequest = app(FarmerDataRequest::class);
        $validated = $validatedRequest->validated();

        // Merge new values with existing farmer data
        $updatedData = array_merge($farmer->toArray(), array_filter($validated, fn($value) => !is_null($value)));
        $farmer->update($updatedData);

        // Update crops if provided
        if ($request->filled('crops')) {
            foreach ($request->crops as $cropData) {
                if (!empty($cropData)) {
                    $existingCrop = $farmer->crops()->first(); // Get current crop data

                    // Ensure all crop fields retain existing values if not provided
                    $cropData = array_merge($existingCrop ? $existingCrop->toArray() : [], array_filter($cropData, fn($value) => !is_null($value)));

                    $farmer->crops()->updateOrCreate(
                        ['farmer_id' => $farmer->farmer_id, 'crop_type' => $cropData['crop_type'] ?? null],
                        $cropData
                    );
                }
            }
        }

        // Update rice if provided
        if ($request->filled('rice')) {
            foreach ($request->rice as $riceData) {
                if (!empty($riceData)) {
                    $existingRice = $farmer->rice()->first(); // Get current rice data

                    // Ensure all rice fields retain existing values if not provided
                    $riceData = array_merge($existingRice ? $existingRice->toArray() : [], array_filter($riceData, fn($value) => !is_null($value)));

                    $farmer->rice()->updateOrCreate(
                        ['farmer_id' => $farmer->farmer_id, 'area_type' => $riceData['area_type'] ?? null],
                        $riceData
                    );
                }
            }
        }

        return response()->json([
            'message' => 'Farmer data updated successfully!',
            'data' => $farmer->load(['crops', 'rice']),
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        Log::error('Error updating farmer data: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        return response()->json([
            'error' => 'Server error',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            $farmer = Farmer::findOrFail($id);

            // Delete related crops & rice
            $farmer->crops()->delete();
            $farmer->rice()->delete();

            $farmer->delete();

            return response()->json(['message' => 'Farmer deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting farmer: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete specific crop
    public function destroyCrop($id)
    {
        try {
            $crop = Crops::findOrFail($id);
            $crop->delete();

            return response()->json(['message' => 'Crop deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting crop: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete specific rice entry
    public function destroyRice($id)
    {
        try {
            $rice = Rice::findOrFail($id);
            $rice->delete();

            return response()->json(['message' => 'Rice deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting rice: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

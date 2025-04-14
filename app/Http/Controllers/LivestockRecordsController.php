<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\LivestockRecord;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Http\Requests\LivestockRecordsDataRequest; // Import the request class

class LivestockRecordsController extends Controller
{
    /**
     * Display a listing of livestock records.
     */
    public function index()
    {
        return response()->json(LivestockRecord::all(), 200);
    }

    /**
     * Store a new livestock record.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Incoming Request Data:', $request->all()); // Log the request
    
            $validatedRequest = app(LivestockRecordsDataRequest::class);
            $validated = $validatedRequest->validated();
    
            Log::info('Validated Data:', $validated); // Log validated data
    
            // Create or fetch the Farmer
            $farmer = isset($validated['farmer_id'])
                ? Farmer::find($validated['farmer_id'])
                : Farmer::create($validated);
    
            if (!$farmer) {
                return response()->json(['error' => 'Farmer not found.'], 404);
            }
    
            // Store Livestock Records if provided
            if (!empty($validated['livestock_records'])) {
                foreach ($validated['livestock_records'] as $livestockData) {
                    Log::info('Storing Livestock Record:', $livestockData);
                    $farmer->livestockRecords()->create($livestockData);
                }
            }
    
            return response()->json([
                'message' => 'Farmer and livestock records saved successfully!',
                'data' => $farmer->load('livestockRecords'),
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error: ' . json_encode($e->errors()));
    
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('Error storing farmer and livestock data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    /**
     * Display a specific livestock record.
     */
    public function show($id)
    {
        $livestockRecord = LivestockRecord::find($id);

        if (!$livestockRecord) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($livestockRecord, 200);
    }

    /**
     * Update a specific livestock record.
     */
    public function update(LivestockRecordsDataRequest $request, $id)
    {
        try {
            // Find the livestock record
            $livestockRecord = LivestockRecord::findOrFail($id);
    
            // Get validated data
            $validated = $request->validated();
    
            // Extract livestock data
            if (!empty($validated['livestock_records']) && is_array($validated['livestock_records'])) {
                $livestockData = $validated['livestock_records'][0]; // Get the first record
                
                // Update the livestock record with new data
                $livestockRecord->update($livestockData);
                
                return response()->json([
                    'message' => 'Livestock record updated successfully!',
                    'data' => $livestockRecord,
                ], 200);
            }
    
            return response()->json([
                'error' => 'No livestock data provided',
            ], 422);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('Error updating livestock record: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
    
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Delete a specific livestock record.
     */
    public function destroy($id)
    {
        $livestockRecord = LivestockRecord::find($id);

        if (!$livestockRecord) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $livestockRecord->delete();

        return response()->json(['message' => 'Livestock record deleted successfully!'], 200);
    }
}

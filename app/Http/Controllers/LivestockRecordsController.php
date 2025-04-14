<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\LivestockRecord;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Http\Requests\LivestockRecordsDataRequest;

class LivestockRecordsController extends Controller
{
    /**
     * Display a listing of livestock records.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        // Create cache key based on request parameters
        $cacheKey = "livestock_p{$page}_pp{$perPage}_s" . md5($search);
        
        return Cache::remember($cacheKey, 300, function () use ($page, $perPage, $search) {
            $query = LivestockRecord::query();
            
            // Only select necessary columns
            $query->select('id', 'farmer_id', 'animal_type', 'count', 'created_at', 'updated_at');
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('animal_type', 'LIKE', "%{$search}%");
                });
            }
            
            $records = $query->paginate($perPage);
            
            return response()->json($records, 200);
        });
    }

    /**
     * Store a new livestock record.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Incoming Request Data:', $request->all());
    
            $validatedRequest = app(LivestockRecordsDataRequest::class);
            $validated = $validatedRequest->validated();
    
            Log::info('Validated Data:', $validated);
            
            return DB::transaction(function () use ($validated) {
                // Create or fetch the Farmer
                $farmer = isset($validated['farmer_id'])
                    ? Farmer::find($validated['farmer_id'])
                    : Farmer::create($validated);
        
                if (!$farmer) {
                    return response()->json(['error' => 'Farmer not found.'], 404);
                }
        
                // Store Livestock Records if provided
                if (!empty($validated['livestock_records'])) {
                    $farmer->livestockRecords()->createMany($validated['livestock_records']);
                }
                
                // Clear relevant caches
                $this->clearLivestockCaches();
                $this->clearFarmerCaches($farmer->id);
        
                return response()->json([
                    'message' => 'Farmer and livestock records saved successfully!',
                    'data' => $farmer->load('livestockRecords'),
                ], 201);
            });
    
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
        // Cache individual livestock record
        $cacheKey = "livestock_{$id}";
        
        return Cache::remember($cacheKey, 300, function () use ($id) {
            $livestockRecord = LivestockRecord::findOrFail($id);
            return response()->json($livestockRecord, 200);
        });
    }

    /**
     * Update a specific livestock record.
     */
    public function update(LivestockRecordsDataRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                // Find the livestock record
                $livestockRecord = LivestockRecord::findOrFail($id);
        
                // Get validated data
                $validated = $request->validated();
        
                // Extract livestock data
                if (!empty($validated['livestock_records']) && is_array($validated['livestock_records'])) {
                    $livestockData = $validated['livestock_records'][0]; // Get the first record
                    
                    // Update the livestock record with new data
                    $livestockRecord->update($livestockData);
                    
                    // Clear relevant caches
                    $this->clearLivestockCaches();
                    $this->clearFarmerCaches($livestockRecord->farmer_id);
                    
                    return response()->json([
                        'message' => 'Livestock record updated successfully!',
                        'data' => $livestockRecord,
                    ], 200);
                }
        
                return response()->json([
                    'error' => 'No livestock data provided',
                ], 422);
            });
    
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
        try {
            return DB::transaction(function () use ($id) {
                $livestockRecord = LivestockRecord::findOrFail($id);
                $farmerId = $livestockRecord->farmer_id;
                
                $livestockRecord->delete();
                
                // Clear relevant caches
                $this->clearLivestockCaches();
                $this->clearFarmerCaches($farmerId);
                
                return response()->json(['message' => 'Livestock record deleted successfully!'], 200);
            });
        } catch (\Exception $e) {
            Log::error('Error deleting livestock record: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Clear livestock-related caches
     */
    private function clearLivestockCaches()
    {
        // Clear pattern-based caches for paginated results
        $keys = Cache::get('livestock_cache_keys', []);
        foreach ($keys as $key) {
            if (strpos($key, 'livestock_p') === 0) {
                Cache::forget($key);
            }
        }
    }
    
    /**
     * Clear farmer-related caches
     */
    private function clearFarmerCaches($farmerId = null)
    {
        // Clear list caches
        Cache::forget('farmers_list');
        
        // Clear pattern-based caches for paginated results
        $keys = Cache::get('farmers_cache_keys', []);
        foreach ($keys as $key) {
            if (strpos($key, 'farmers_p') === 0) {
                Cache::forget($key);
            }
        }
        
        // Clear specific farmer cache if ID provided
        if ($farmerId) {
            Cache::forget("farmer_{$farmerId}");
        }
    }
}
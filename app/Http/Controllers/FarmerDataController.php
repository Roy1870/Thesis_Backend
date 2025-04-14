<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        // Create cache key based on request parameters
        $cacheKey = "farmers_p{$page}_pp{$perPage}_s" . md5($search);
        
        return Cache::remember($cacheKey, 300, function () use ($page, $perPage, $search) {
            $query = Farmer::query();
            
            // Only select necessary columns for the list view
            $query->select('farmer_id', 'name', 'contact_number', 'created_at', 'updated_at');
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('contact_number', 'LIKE', "%{$search}%");
                });
            }
            
            // Only eager load relationships when needed
            // For list view, we might not need all relationship data
            $farmers = $query->paginate($perPage);
            
            return response()->json($farmers, 200);
        });
    }

    public function store(FarmerDataRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Use database transaction for multiple operations
            return DB::transaction(function () use ($validated, $request) {
                $farmer = Farmer::create($validated);

                if (!empty($request->crops)) {
                    $farmer->crops()->createMany($request->crops);
                }

                if (!empty($request->rice)) {
                    $farmer->rice()->createMany($request->rice);
                }
                
                // Clear relevant caches
                $this->clearFarmerCaches();
                
                // Load relationships only when needed
                return response()->json([
                    'message' => 'Farmer data saved successfully!',
                    'data' => $farmer->load(['crops', 'rice']),
                ], 201);
            });
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
            return DB::transaction(function () use ($request, $farmerId) {
                $farmer = Farmer::findOrFail($farmerId);
                $farmer->crops()->createMany($request->crops);
                
                // Clear relevant caches
                $this->clearFarmerCaches($farmerId);
                
                return response()->json([
                    'message' => 'Crops stored successfully!',
                    'data' => $farmer->load('crops'),
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Error storing crops: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeRice(RiceDataRequest $request, $farmerId)
    {
        try {
            return DB::transaction(function () use ($request, $farmerId) {
                $farmer = Farmer::findOrFail($farmerId);
                $farmer->rice()->createMany($request->rice);
                
                // Clear relevant caches
                $this->clearFarmerCaches($farmerId);
                
                return response()->json([
                    'message' => 'Rice stored successfully!',
                    'data' => $farmer->load('rice'),
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Error storing rice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        // Cache individual farmer data
        $cacheKey = "farmer_{$id}";
        
        return Cache::remember($cacheKey, 300, function () use ($id) {
            // Use specific eager loading with select statements
            $farmer = Farmer::with([
                'crops:crop_id,farmer_id,crop_name,area,unit',
                'rice:rice_id,farmer_id,variety,area,unit'
            ])->findOrFail($id);
            
            return response()->json($farmer, 200);
        });
    }

    public function updateFarmer(FarmerDataRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $farmer = Farmer::findOrFail($id);
                $validated = $request->validated();
                $farmer->update($validated);
                
                // Clear relevant caches
                $this->clearFarmerCaches($id);
                
                return response()->json([
                    'message' => 'Farmer updated successfully!',
                    'data' => $farmer,
                ], 200);
            });
        } catch (\Exception $e) {
            Log::error('Error updating farmer: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRice(RiceDataRequest $request, $farmerId, $riceId)
    {
        try {
            return DB::transaction(function () use ($request, $farmerId, $riceId) {
                // Use a join to efficiently find the rice record
                $rice = Rice::where('rice_id', $riceId)
                    ->where('farmer_id', $farmerId)
                    ->firstOrFail();

                if (!empty($request->rice) && is_array($request->rice) && count($request->rice) > 0) {
                    $riceData = $request->rice[0];
                    $rice->update($riceData);
                    
                    // Clear relevant caches
                    $this->clearFarmerCaches($farmerId);
                    
                    return response()->json([
                        'message' => 'Rice updated successfully!',
                        'data' => $rice,
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Invalid data',
                        'message' => 'No rice data provided',
                    ], 422);
                }
            });
        } catch (\Exception $e) {
            Log::error('Error updating rice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCrops(CropsDataRequest $request, $farmerId, $cropId)
    {
        try {
            return DB::transaction(function () use ($request, $farmerId, $cropId) {
                // Use a join to efficiently find the crop record
                $crop = Crops::where('crop_id', $cropId)
                    ->where('farmer_id', $farmerId)
                    ->firstOrFail();

                if (!empty($request->crops) && is_array($request->crops) && count($request->crops) > 0) {
                    $cropData = $request->crops[0];
                    $crop->update($cropData);
                    
                    // Clear relevant caches
                    $this->clearFarmerCaches($farmerId);
                    
                    return response()->json([
                        'message' => 'Crops updated successfully!',
                        'data' => $crop,
                    ], 200);
                } else {
                    return response()->json([
                        'error' => 'Invalid data',
                        'message' => 'No crop data provided',
                    ], 422);
                }
            });
        } catch (\Exception $e) {
            Log::error('Error updating crops: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyFarmer($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $farmer = Farmer::findOrFail($id);
                
                // Use more efficient deletion
                $farmer->crops()->delete();
                $farmer->rice()->delete();
                $farmer->delete();
                
                // Clear relevant caches
                $this->clearFarmerCaches($id);
                
                return response()->json(['message' => 'Farmer deleted successfully'], 200);
            });
        } catch (\Exception $e) {
            Log::error('Error deleting farmer: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCrop($farmerId, $cropId)
    {
        try {
            return DB::transaction(function () use ($farmerId, $cropId) {
                $crop = Crops::where('crop_id', $cropId)
                    ->where('farmer_id', $farmerId)
                    ->firstOrFail();
                    
                $crop->delete();
                
                // Clear relevant caches
                $this->clearFarmerCaches($farmerId);
                
                return response()->json(['message' => 'Crop deleted successfully'], 200);
            });
        } catch (\Exception $e) {
            Log::error('Error deleting crop: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyRice($farmerId, $riceId)
    {
        try {
            return DB::transaction(function () use ($farmerId, $riceId) {
                $rice = Rice::where('rice_id', $riceId)
                    ->where('farmer_id', $farmerId)
                    ->firstOrFail();
                    
                $rice->delete();
                
                // Clear relevant caches
                $this->clearFarmerCaches($farmerId);
                
                return response()->json(['message' => 'Rice deleted successfully'], 200);
            });
        } catch (\Exception $e) {
            Log::error('Error deleting rice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
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
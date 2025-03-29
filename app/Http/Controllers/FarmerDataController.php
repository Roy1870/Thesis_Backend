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
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $query = Farmer::with(['crops', 'rice']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_number', 'LIKE', "%{$search}%");
            });
        }

        $farmers = $query->paginate($perPage);
        return response()->json($farmers, 200);
    }

    public function store(FarmerDataRequest $request)
    {
        try {
            $validated = $request->validated();
            $farmer = Farmer::create($validated);

            if (!empty($request->crops)) {
                foreach ($request->crops as $cropData) {
                    $farmer->crops()->create($cropData);
                }
            }

            if (!empty($request->rice)) {
                foreach ($request->rice as $riceData) {
                    $farmer->rice()->create($riceData);
                }
            }

            return response()->json([
                'message' => 'Farmer data saved successfully!',
                'data' => $farmer->load(['crops', 'rice']),
            ], 201);
        } catch (\Exception $e) {
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

    public function updateFarmer(FarmerDataRequest $request, $id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            $validated = $request->validated();

            $farmer->update($validated);

            return response()->json([
                'message' => 'Farmer updated successfully!',
                'data' => $farmer,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateRice(RiceDataRequest $request, $farmerId, $riceId)
    {
        try {
            $farmer = Farmer::findOrFail($farmerId);
            $rice = Rice::where('rice_id', $riceId)->where('farmer_id', $farmerId)->firstOrFail();

            // Extract the first rice entry from the request
            if (!empty($request->rice) && is_array($request->rice) && count($request->rice) > 0) {
                $riceData = $request->rice[0];
                $rice->update($riceData);

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
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCrops(CropsDataRequest $request, $farmerId, $cropId)
    {
        try {
            $farmer = Farmer::findOrFail($farmerId);
            $crop = Crops::where('crop_id', $cropId)->where('farmer_id', $farmerId)->firstOrFail();

            // Extract the first crop entry from the request
            if (!empty($request->crops) && is_array($request->crops) && count($request->crops) > 0) {
                $cropData = $request->crops[0];
                $crop->update($cropData);

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
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyFarmer($id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            $farmer->crops()->delete();
            $farmer->rice()->delete();
            $farmer->delete();

            return response()->json(['message' => 'Farmer deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCrop($farmerId, $cropId)
    {
        try {
            $crop = Crops::where('crop_id', $cropId)->where('farmer_id', $farmerId)->firstOrFail();
            $crop->delete();

            return response()->json(['message' => 'Crop deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyRice($farmerId, $riceId)
    {
        try {
            $rice = Rice::where('rice_id', $riceId)->where('farmer_id', $farmerId)->firstOrFail();
            $rice->delete();

            return response()->json(['message' => 'Rice deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }
}


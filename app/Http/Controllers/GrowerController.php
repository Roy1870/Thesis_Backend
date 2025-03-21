<?php

namespace App\Http\Controllers;

use App\Models\Grower;
use App\Models\Crops;
use App\Models\Rice;
use App\Models\Farmer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGrowerRequest;
use Illuminate\Support\Facades\DB;

class GrowerController extends Controller
{
    /**
     * Display a listing of the growers with related crops and rice.
     */
    public function index()
    {
        $growers = Grower::with(['crops', 'rice'])->get();
        return response()->json($growers, 200);
    }

    /**
     * Store a newly created grower along with crops and rice.
     */
   public function store(StoreGrowerRequest $request)
{
    $validatedData = $request->validated();
    DB::beginTransaction();

    try {
        $farmer = null;

        // Create farmer only if provided
        if (isset($validatedData['farmer'])) {
            $farmer = Farmer::create($validatedData['farmer']);
        }

        // Create grower (farmer_id is optional)
        $grower = Grower::create([
            'farmer_id' => $farmer ? $farmer->farmer_id : null
        ]);

        $growerId = $grower->grower_id;
        $createdCrops = [];
        $createdRice = [];

        // Create crops if provided
        if (!empty($validatedData['crops'])) {
            foreach ($validatedData['crops'] as $cropData) {
                $cropData['grower_id'] = $growerId;
                $createdCrops[] = Crops::create($cropData);
            }
        }

        // Create rice records if provided
        if (!empty($validatedData['rice'])) {
            foreach ($validatedData['rice'] as $riceData) {
                $riceData['grower_id'] = $growerId;
                $createdRice[] = Rice::create($riceData);
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Grower and related records created successfully!',
            'grower' => $grower,
            'crops' => $createdCrops,
            'rice' => $createdRice
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Something went wrong.',
            'details' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified grower along with crops and rice.
     */
    public function show($id)
    {
        $grower = Grower::with(['crops', 'rice'])->find($id);

        if (!$grower) {
            return response()->json(['error' => 'Grower not found'], 404);
        }

        return response()->json($grower, 200);
    }

    /**
     * Update the specified grower and optionally update crops and rice.
     */
    public function update(Request $request, $id)
    {
        $grower = Grower::find($id);
    
        if (!$grower) {
            return response()->json(['error' => 'Grower not found'], 404);
        }
    
        DB::beginTransaction();
    
        try {
            // ✅ UPDATE GROWER
            if ($request->has('farmer_id')) {
                $grower->update(['farmer_id' => $request->farmer_id]);
            }
    
            // ✅ UPDATE OR CREATE CROPS
            if ($request->has('crops')) {
                $existingCropIds = collect($request->crops)->pluck('crop_id')->filter()->toArray();
                
                // Delete removed crops
                Crops::where('grower_id', $grower->grower_id)
                    ->whereNotIn('crop_id', $existingCropIds)
                    ->delete();
    
                foreach ($request->crops as $cropData) {
                    if (isset($cropData['crop_id'])) {
                        // Update existing crop
                        Crops::where('crop_id', $cropData['crop_id'])->update($cropData);
                    } else {
                        // Create new crop
                        $cropData['grower_id'] = $grower->grower_id;
                        Crops::create($cropData);
                    }
                }
            }
    
            // ✅ UPDATE OR CREATE RICE
            if ($request->has('rice')) {
                $existingRiceIds = collect($request->rice)->pluck('rice_id')->filter()->toArray();
    
                // Delete removed rice records
                Rice::where('grower_id', $grower->grower_id)
                    ->whereNotIn('rice_id', $existingRiceIds)
                    ->delete();
    
                foreach ($request->rice as $riceData) {
                    if (isset($riceData['rice_id'])) {
                        // Update existing rice entry
                        Rice::where('rice_id', $riceData['rice_id'])->update($riceData);
                    } else {
                        // Create new rice entry
                        $riceData['grower_id'] = $grower->grower_id;
                        Rice::create($riceData);
                    }
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Grower and related records updated successfully!',
                'grower' => $grower->load(['crops', 'rice'])
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Update failed.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified grower along with related crops and rice.
     */
    public function destroy($id)
    {
        $grower = Grower::find($id);

        if (!$grower) {
            return response()->json(['error' => 'Grower not found'], 404);
        }

        DB::beginTransaction();

        try {
            Crops::where('grower_id', $grower->grower_id)->delete();
            Rice::where('grower_id', $grower->grower_id)->delete();
            $grower->delete();

            DB::commit();

            return response()->json(['message' => 'Grower and related records deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Delete failed.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

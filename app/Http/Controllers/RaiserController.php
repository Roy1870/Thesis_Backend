<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use App\Models\Farmer;
use App\Models\LivestockRecord;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRaiserRequest;
use Illuminate\Support\Facades\DB;

class RaiserController extends Controller
{
    /**
     * Display a listing of raisers with related farmers and livestock.
     */
    public function index()
    {
        $raisers = Raiser::with(['farmer', 'livestockRecords'])->get();
        return response()->json($raisers, 200);
    }

    /**
     * Store a newly created raiser along with livestock and farmer details.
     */
    public function store(StoreRaiserRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();
    
        try {
            $farmer = null;
    
            // Create farmer only if provided
            if (isset($validatedData['farmer'])) {
                $farmer = Farmer::create($validatedData['farmer']);
            }
    
            // Ensure "raiser" key exists in the request
            $raiserData = $validatedData['raiser'] ?? [];
            
            // Create raiser (farmer_id is optional)
            $raiser = Raiser::create([
                'farmer_id' => $farmer ? $farmer->farmer_id : null,
                'location' => $raiserData['location'] ?? null, // Ensure field existence
                'updated_by' => $raiserData['updated_by'] ?? null,
                'remarks' => $raiserData['remarks'] ?? null
            ]);
    
            $raiserId = $raiser->raiser_id;
            $createdLivestock = [];
    
            // Create livestock records if provided
            if (!empty($validatedData['livestock_records'])) {
                foreach ($validatedData['livestock_records'] as $livestockData) {
                    $livestockData['raiser_id'] = $raiserId;
                    $createdLivestock[] = LivestockRecord::create($livestockData);
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Raiser and related records created successfully!',
                'raiser' => $raiser,
                'livestock' => $createdLivestock
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Farmer::where('raiser_id', $farmer->farmer_id)->delete();
            $farmer->delete();
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified raiser along with farmer and livestock records.
     */
    public function show($id)
    {
        $raiser = Raiser::with(['farmer', 'livestockRecords'])->find($id);

        if (!$raiser) {
            return response()->json(['error' => 'Raiser not found'], 404);
        }

        return response()->json($raiser, 200);
    }

    /**
     * Update the specified raiser and optionally update livestock records.
     */
    public function update(Request $request, $id)
    {
        $raiser = Raiser::find($id);

        if (!$raiser) {
            return response()->json(['error' => 'Raiser not found'], 404);
        }

        DB::beginTransaction();

        try {
            // ✅ UPDATE RAISER
            $raiser->update($request->only(['farmer_id', 'location', 'updated_by', 'remarks']));

            // ✅ UPDATE OR CREATE LIVESTOCK RECORDS
            if ($request->has('livestock')) {
                $existingLivestockIds = collect($request->livestock)->pluck('record_id')->filter()->toArray();

                // Delete removed livestock records
                LivestockRecord::where('raiser_id', $raiser->raiser_id)
                    ->whereNotIn('record_id', $existingLivestockIds)
                    ->delete();

                foreach ($request->livestock as $livestockData) {
                    if (isset($livestockData['record_id'])) {
                        // Update existing livestock record
                        LivestockRecord::where('record_id', $livestockData['record_id'])->update($livestockData);
                    } else {
                        // Create new livestock record
                        $livestockData['raiser_id'] = $raiser->raiser_id;
                        LivestockRecord::create($livestockData);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Raiser and related records updated successfully!',
                'raiser' => $raiser->load(['farmer', 'livestockRecords'])
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
     * Remove the specified raiser along with related livestock records.
     */
    public function destroy($id)
    {
        $raiser = Raiser::find($id);

        if (!$raiser) {
            return response()->json(['error' => 'Raiser not found'], 404);
        }

        DB::beginTransaction();

        try {
            LivestockRecord::where('raiser_id', $raiser->raiser_id)->delete();
            $raiser->delete();

            DB::commit();

            return response()->json(['message' => 'Raiser and related records deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Delete failed.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

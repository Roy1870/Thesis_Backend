<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Farmer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOperatorRequest;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    /**
     * Display a listing of operators with related farmers.
     */
    public function index()
    {
        $operators = Operator::with('farmer')->get();
        return response()->json($operators, 200);
    }

    /**
     * Store a newly created operator along with farmer details.
     */
    public function store(StoreOperatorRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction();

        try {
            $farmer = null;

            // Create farmer only if provided
            if (isset($validatedData['farmer'])) {
                $farmer = Farmer::create($validatedData['farmer']);
            }

            // Ensure "operator" key exists in the request
            $operatorData = $validatedData['operator'] ?? [];

            // Create operator (farmer_id is optional)
            $operator = Operator::create([
                'farmer_id' => $farmer ? $farmer->farmer_id : null,
                'fishpond_location' => $operatorData['fishpond_location'] ?? null,
                'geotagged_photo_url' => $operatorData['geotagged_photo_url'] ?? null,
                'cultured_species' => $operatorData['cultured_species'] ?? null,
                'productive_area_sqm' => $operatorData['productive_area_sqm'] ?? null,
                'stocking_density' => $operatorData['stocking_density'] ?? null,
                'date_of_stocking' => $operatorData['date_of_stocking'] ?? null,
                'production_kg' => $operatorData['production_kg'] ?? null,
                'date_of_harvest' => $operatorData['date_of_harvest'] ?? null,
                'operational_status' => $operatorData['operational_status'] ?? null,
                'remarks' => $operatorData['remarks'] ?? null
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Operator and related records created successfully!',
                'operator' => $operator
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
     * Display the specified operator along with farmer details.
     */
    public function show($id)
    {
        $operator = Operator::with('farmer')->find($id);

        if (!$operator) {
            return response()->json(['error' => 'Operator not found'], 404);
        }

        return response()->json($operator, 200);
    }

    /**
     * Update the specified operator.
     */
    public function update(Request $request, $id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['error' => 'Operator not found'], 404);
        }

        DB::beginTransaction();

        try {
            // Update operator fields
            $operator->update($request->only([
                'farmer_id',
                'fishpond_location',
                'geotagged_photo_url',
                'cultured_species',
                'productive_area_sqm',
                'stocking_density',
                'date_of_stocking',
                'production_kg',
                'date_of_harvest',
                'operational_status',
                'remarks'
            ]));

            DB::commit();

            return response()->json([
                'message' => 'Operator updated successfully!',
                'operator' => $operator->load('farmer')
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
     * Remove the specified operator.
     */
    public function destroy($id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['error' => 'Operator not found'], 404);
        }

        DB::beginTransaction();

        try {
            $operator->delete();
            DB::commit();

            return response()->json(['message' => 'Operator deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Delete failed.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

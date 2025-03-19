<?php

namespace App\Http\Controllers;

use App\Models\LivestockRecord;
use Illuminate\Http\Request;
use App\Http\Requests\LivestockRecordsDataRequest; // Import the request class

class LivestockRecordController extends Controller
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
    public function store(LivestockRecordsDataRequest $request)
    {
        $livestockRecord = LivestockRecord::create($request->validated());

        return response()->json([
            'message' => 'Livestock record created successfully!',
            'data' => $livestockRecord
        ], 201);
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
        $livestockRecord = LivestockRecord::find($id);

        if (!$livestockRecord) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $livestockRecord->update($request->validated());

        return response()->json([
            'message' => 'Livestock record updated successfully!',
            'data' => $livestockRecord
        ], 200);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Models\Grower;
use App\Models\Operator;
use App\Models\Raiser;
use App\Http\Requests\FarmerDataRequest;
use App\Http\Requests\GrowersDataRequest;
use App\Http\Requests\RaiserDataRequest;
use App\Http\Requests\OperatorDataRequest;

class FarmerDataController extends Controller
{
    protected $models = [
        'farmers' => [FarmerDataRequest::class, Farmer::class],
        'growers' => [GrowersDataRequest::class, Grower::class],
        'operators' => [OperatorDataRequest::class, Operator::class],
        'raisers' => [RaiserDataRequest::class, Raiser::class],
    ];

    public function index($type)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        return response()->json($this->models[$type][1]::all(), 200);
    }

    public function store(Request $request, $type)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        [$requestClass, $modelClass] = $this->models[$type];
        $validatedRequest = app($requestClass);
        $validated = $validatedRequest->validated();

        $record = $modelClass::create($validated);

        return response()->json($record, 201);
    }

    public function show($type, $id)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        $record = $this->models[$type][1]::findOrFail($id);
        return response()->json($record, 200);
    }

    public function update(Request $request, $type, $id)
{
    if (!isset($this->models[$type])) {
        return response()->json(['error' => 'Invalid type'], 400);
    }
    
    [$requestClass, $modelClass] = $this->models[$type];

    // Validate input (ensure non-null values)
    $validatedRequest = app($requestClass);
    $validated = array_filter($validatedRequest->validated(), fn($value) => !is_null($value));

    // Find record
    $record = $modelClass::findOrFail($id);

    // Only update fields present in request
    if (!empty($validated)) {
        $record->update($validated);
    }

    return response()->json($record, 200);
}


    public function destroy($type, $id)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        $record = $this->models[$type][1]::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

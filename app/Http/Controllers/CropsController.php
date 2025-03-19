<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CropsDataRequest;

class CropsController extends Controller
{
    /**
     * Display a listing of crops.
     */
    public function index()
    {
        return response()->json(Crop::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created crop.
     */
    public function store(CropsDataRequest $request)
    {
        $crop = Crop::create($request->validated());
        return response()->json($crop, Response::HTTP_CREATED);
    }

    /**
     * Display a specific crop.
     */
    public function show($id)
    {
        $crop = Crop::find($id);
        
        if (!$crop) {
            return response()->json(['message' => 'Crop not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($crop, Response::HTTP_OK);
    }

    /**
     * Update an existing crop.
     */
    public function update(CropsDataRequest $request, $id)
    {
        $crop = Crop::find($id);
        
        if (!$crop) {
            return response()->json(['message' => 'Crop not found'], Response::HTTP_NOT_FOUND);
        }
        
        $crop->update($request->validated());
        return response()->json($crop, Response::HTTP_OK);
    }

    /**
     * Remove a crop from storage.
     */
    public function destroy($id)
    {
        $crop = Crop::find($id);
        
        if (!$crop) {
            return response()->json(['message' => 'Crop not found'], Response::HTTP_NOT_FOUND);
        }
        
        $crop->delete();
        return response()->json(['message' => 'Crop deleted successfully'], Response::HTTP_OK);
    }
}
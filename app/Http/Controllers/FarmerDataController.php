<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Grower;
use App\Http\Requests\FarmerDataRequest;
use App\Http\Requests\GrowersDataRequest;
use Illuminate\Http\Request;

class FarmerDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexFarmer()
    {
        return Farmer::all();
    }

    public function indexGrower()
    {
        return Grower::all();
    }

    /**
     * Show the form for creating a new Farmer.
     */
    public function createFarmer()
    {
    }

    /**
     * Store a newly created Farmer in storage.
     */
    public function farmerstore(FarmerDataRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        $farmer = Farmer::create($validated);

        return response()->json($farmer, 201);
    }

    /**
     * Show the form for creating a new Grower.
     */
    public function createGrower()
    {
    }

    /**
     * Store a newly created Grower in storage.
     */
    public function growerstore(GrowersDataRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        $grower = Grower::create($validated);

        return response()->json($grower, 201);
    }

    /**
     * Display the specified Farmer.
     */
    public function showFarmer(string $id)
    {
        return Farmer::findOrFail($id);
    }

    /**
     * Display the specified Grower.
     */
    public function showGrower(string $id)
    {
        return Grower::findOrFail($id);
    }

    /**
     * Show the form for editing a Farmer.
     */
    public function editFarmer(string $id)
    {
        $farmer = Farmer::findOrFail($id);

        return view('farmer.edit', compact('farmer'));
    }

    /**
     * Update the specified Farmer in storage.
     */
    public function updateFarmer(string $id, FarmerDataRequest $request)
    {
        $farmer = Farmer::findOrFail($id);

        // Retrieve validated input data
        $validated = $request->validated();

        $farmer->fname = $validated['fname'] ?? $farmer->fname;
        $farmer->lname = $validated['lname'] ?? $farmer->lname;
        $farmer->email = $validated['email'] ?? $farmer->email;
        $farmer->home_address = $validated['home_address'] ?? $farmer->home_address;
        $farmer->farm_address = $validated['farm_address'] ?? $farmer->farm_address;

        $farmer->save();

        return response()->json($farmer, 200);
    }

    /**
     * Show the form for editing a Grower.
     */
    public function editGrower(string $id)
    {
        $grower = Grower::findOrFail($id);

        return view('grower.edit', compact('grower'));
    }

    /**
     * Update the specified Grower in storage.
     */
    public function updateGrower(string $id, GrowersDataRequest $request)
    {
        $grower = Grower::findOrFail($id);

        // Retrieve validated input data
        $validated = $request->validated();

        $grower->crop_name = $validated['crop_name'] ?? $grower->crop_name;
        $grower->area_hectares = $validated['area_hectares'] ?? $grower->area_hectares;
        $grower->yield = $validated['yield'] ?? $grower->yield;
        $grower->season = $validated['season'] ?? $grower->season;
        $grower->market_outlet = $validated['market_outlet'] ?? $grower->market_outlet;


        // Add other fields if necessary

        $grower->save();

        return response()->json($grower, 200);
    }

    /**
     * Remove the specified Farmer from storage.
     */
    public function destroyFarmer(string $id)
    {
        $farmer = Farmer::findOrFail($id);

        $farmer->delete();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified Grower from storage.
     */
    public function destroyGrower(string $id)
    {
        $grower = Grower::findOrFail($id);

        $grower->delete();

        return response()->json(null, 204);
    }
}

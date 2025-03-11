<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Grower;
use App\Models\Operator;
use App\Http\Requests\FarmerDataRequest;
use App\Http\Requests\GrowersDataRequest;
use App\Http\Requests\OperatorDataRequest;
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

    public function indexOperator()
    {
        return Operator::all();
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
     * Store a newly created Operator in storage.
     */
    public function operatorstore(OperatorDataRequest $request)
    {
        // Retrieve the validated input data
        $validated = $request->validated();

        $operator = Operator::create($validated);

        return response()->json($operator, 201);
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
     * Display the specified Grower.
     */
    public function showOperator(string $id)
    {
        return Operator::findOrFail($id);
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
     * Update the specified Operator in storage.
     */
    public function updateOperator(string $id, OperatorDataRequest $request)
    {
        $operator = Operator::findOrFail($id);

        // Retrieve validated input data
        $validated = $request->validated();

        $operator->fishpond_location = $validated['fishpond_location'] ?? $operator->fishpond_location;
        $operator->cultured_species = $validated['cultured_species'] ?? $operator->cultured_species;
        $operator->productive_area = $validated['productive_area'] ?? $operator->productive_area;
        $operator->stocking_density = $validated['stocking_density'] ?? $operator->stocking_density;
        $operator->production = $validated['production'] ?? $operator->production;
        $operator->harvest_date = $validated['harvest_date'] ?? $operator->harvest_date;
        $operator->month = $validated['month'] ?? $operator->month;
        $operator->year = $validated['year'] ?? $operator->year;

        // Add other fields if necessary

        $operator->save();

        return response()->json($operator, 200);
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

    /**
     * Remove the specified Operator from storage.
     */
    public function destroyOperator(string $id)
    {
        $operator = Operator::findOrFail($id);

        $operator->delete();

        return response()->json(null, 204);
    }
}

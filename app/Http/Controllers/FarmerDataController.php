<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Http\Requests\FarmerDataRequest;
use Illuminate\Http\Request;

class FarmerDataController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Farmer::all();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FarmerDataRequest $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        $Farmer = Farmer::create($validated);

        return $Farmer;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Farmer::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmerDataRequest $request, string $id)
{
    $validated = $request->validated();

    $Farmer = Farmer::findOrFail($id);

    $Farmer->fname = $validated['fname'] ?? $Farmer->fname;
    $Farmer->lname = $validated['lname'] ?? $Farmer->lname;
    $Farmer->email = $validated['email'] ?? $Farmer->email;
    $Farmer->home_address = $validated['home_address'] ?? $Farmer->home_address;
    $Farmer->farm_address = $validated['farm_address'] ?? $Farmer->farm_address;

    $Farmer->save();

    return $Farmer;
}


    /**
     * Update the specified resource in storage.
     */
    public function update(FarmerDataRequest $request ,string $id)
    {
        $Farmer = Farmer::findOrFail($id);

        $validated = $request->validated();

        $Farmer->name = $validated['name'];

        $Farmer->save();

        return $Farmer;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTextsRequest;
use App\Http\Requests\UpdateTextsRequest;
use App\Models\Texts;

class TextsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTextsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Texts $texts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Texts $texts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTextsRequest $request, Texts $texts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Texts $texts)
    {
        //
    }
}

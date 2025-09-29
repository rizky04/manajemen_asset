<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetPublicController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
 public function show(Asset $asset)
    {
        $asset->load(['transactions', 'company', 'employee', 'location']);

        return view('assets.public-show', compact('asset'));
    }


public function bulkPrint($ids)
{
    $ids = explode(',', $ids);

    $assets = Asset::whereIn('id', $ids)->get();

    return view('assets.qr-bulk', compact('assets'));
}

public function printAll()
{
    $assets = Asset::all();

    return view('assets.qr-all', compact('assets'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

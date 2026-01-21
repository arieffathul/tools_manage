<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Engineer\StoreEngineerRequest;
use App\Models\Engineer;
use Illuminate\Http\Request;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $engineers = Engineer::all();

        return view('master.engineer.index', compact('engineers'));
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
    public function store(StoreEngineerRequest $request)
    {
        try {
            $data = $request->validated();

            $engineer = new Engineer;
            $engineer->name = $data['name'];
            $engineer->shift = $data['shift'];
            $engineer->status = $data['status'];
            $engineer->inactivated_at = $data['inactivated_at'];

            $engineer->save();

            return redirect()->route('master.engineer.index')->with('success', 'Engineer berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $response = $this->default_response;
        // try{

        // }

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

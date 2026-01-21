<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Engineer\StoreEngineerRequest;
use App\Http\Requests\Engineer\UpdateEngineerRequest;
use App\Models\Engineer;
use Exception;

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
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->default_response;
        try {
            $engineer = Engineer::find($id);
            $response['success'] = true;
            $response['data'] = ['engineer' => $engineer];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $engineer = Engineer::findOrFail($id);

        return view('master.engineer.edit', compact('engineers'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEngineerRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $engineer = Engineer::where('id', $id)->first();
            if ($engineer) {
                $engineer->name = $data['name'];
                $engineer->shift = $data['shift'];
                $engineer->status = $data['status'];
                $engineer->inactivated_at = $data['inactivated_at'];

                $engineer->save();

                return redirect()->route('master.engineer.index')->with('success', 'Engineer berhasil diupdate.');
            } else {
                return redirect()->route('master.engineer.index')->with('error', 'Engineer tidak ditemukan.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
            // throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->default_response;
        try {
            $engineer = Engineer::find($id);
            if ($engineer) {
                $engineer->delete();
                $response['success'] = true;
                $response['message'] = 'Engineer berhasil dihapus.';
                $response['data'] = ['engineer' => $engineer];

            } else {
                $response['message'] = 'Engineer tidak ditemukan.';
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
    }
}

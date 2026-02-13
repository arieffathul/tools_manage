<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Engineer\StoreEngineerRequest;
use App\Http\Requests\Engineer\UpdateEngineerRequest;
use App\Models\Engineer;
use Exception;
use Illuminate\Http\Request;

class EngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if we're viewing inactive engineers
        $viewInactive = $request->get('status') == 'inactive';

        // Base query
        $query = Engineer::query();

        // Filter by status (active/inactive)
        $query->where('status', $viewInactive ? 'inactive' : 'active');

        // Filter by shift
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Order by name (A-Z)
        $query->orderBy('name', 'asc');

        // Paginate with query string
        $engineers = $query->paginate(10)->withQueryString();

        return view('master.engineer.engineer', compact('engineers', 'viewInactive'));
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

            Engineer::create($request->validated());

            return redirect()->route('engineer.index')->with('success', 'Engineer berhasil ditambahkan.');
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
            $engineer = Engineer::findOrFail($id);

            return response()->json($engineer);
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
        // $engineer = Engineer::findOrFail($id);

        // return view('master.engineer.edit', compact('engineer'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEngineerRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $engineer = Engineer::findOrFail($id);
            if ($engineer) {
                $engineer->name = $data['name'];
                $engineer->shift = $data['shift'];
                $engineer->status = $data['status'];
                // $engineer->inactivated_at = $data['inactivated_at'];

                $engineer->save();

                return back()->with('success', 'Engineer berhasil diupdate.');
            } else {
                return back()->with('error', 'Engineer tidak ditemukan.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
            // throw $th;
        }
    }

    public function inactive(string $id)
    {
        $engineer = Engineer::findOrFail($id);

        $engineer->update([
            'status' => 'inactive',
            'inactived_at' => now(),
        ]);

        return back()->with('success', 'Engineer dinonaktifkan.');
    }

    public function activate(string $id)
    {
        $engineer = Engineer::findOrFail($id);

        $engineer->update([
            'status' => 'active',
            // 'inactived_at' => null,
        ]);

        return back()->with('success', 'Engineer diaktifkan kembali.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->default_response;
        try {
            $engineer = Engineer::find($id, 'id');
            if ($engineer) {
                $engineer->delete();
                $response['success'] = true;
                $response['message'] = 'Engineer berhasil dihapus.';
                $response['data'] = ['engineer' => $engineer];

            } else {
                $response['message'] = 'Engineer tidak ditemukan.';

            }

            return back()->with('success', 'Engineer berhasil dihapus.');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}

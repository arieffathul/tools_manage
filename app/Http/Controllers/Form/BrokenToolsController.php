<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Broken\StoreBrokenToolsRequest;
use App\Http\Requests\Broken\UpdateBrokenToolsRequest;
use App\Models\BrokenTool;
use App\Models\Engineer;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;

class BrokenToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function select()
    {
        $brokenTools = BrokenTool::with(['tool', 'reporter', 'handler'])
            ->whereNot('status', 'resolved')
            ->get();

        return view('forms.brokenSelect', compact('brokenTools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tools = Tool::all();
        $engineers = Engineer::where('status', 'active')->get();

        return view('forms.brokenTools', compact('tools', 'engineers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrokenToolsRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Handle image upload if exists
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('broken_tools', 'public');
            }

            BrokenTool::create($data);

            $tool = Tool::find($data['tool_id']);
            if ($tool) {
                // Kurangi quantity tool sesuai jumlah yang dilaporkan rusak
                $tool->decrementAllQuantity($data['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('forms.complete')
                ->with('success', 'Laporan alat rusak berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brokenTool = BrokenTool::with('tool')->findOrFail($id);
        $tools = Tool::all();
        $engineers = Engineer::where('status', 'active')->get();

        return view('forms.brokenTools', compact('brokenTool', 'tools', 'engineers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrokenToolsRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $brokenTool = BrokenTool::findOrFail($id);

            // Handle image upload if exists
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('broken_tools', 'public');
            }

            $brokenTool->update($data);

            if ($data['status'] === 'resolved') {
                $tool = Tool::find($data['tool_id']);
                if ($tool) {
                    // Tambah quantity tool sesuai jumlah yang dilaporkan diperbaiki
                    $tool->incrementAllQuantity($data['quantity']);
                }
            }

            DB::commit();

            return redirect()
                ->route('forms.complete')
                ->with('success', 'Laporan alat rusak berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

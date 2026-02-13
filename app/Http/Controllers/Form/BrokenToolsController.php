<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Broken\StoreBrokenToolsRequest;
use App\Http\Requests\Broken\UpdateBrokenToolsRequest;
use App\Models\BrokenTool;
use App\Models\Engineer;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use Symfony\Component\HttpFoundation\Request;

class BrokenToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewResolved = $request->get('status') == 'resolved';
        $query = BrokenTool::with(['tool', 'reporter', 'handler']);

        // ========== FILTER TAB ==========
        if ($viewResolved) {
            $query->where('status', 'resolved');
        } else {
            $query->whereIn('status', ['poor', 'broken', 'scrap']);
        }

        // ========== FILTER STATUS (HANYA UNTUK ONGOING) ==========
        if (! $viewResolved && $request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        // ========== FILTER ENGINEER ==========
        if ($viewResolved && $request->filled('handler_id')) {
            $query->where('handled_by', $request->handler_id);
        }

        if (! $viewResolved && $request->filled('reporter_id')) {
            $query->where('reported_by', $request->reporter_id);
        }

        // ========== FILTER TOOL ==========
        if ($request->filled('tool_id')) {
            $query->where('tool_id', $request->tool_id);
        }

        // ========== FILTER DATE RANGE ==========
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ========== SORTING ==========
        switch ($request->get('sort')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // newest
                $query->latest();
        }

        $brokenTools = $query->paginate(20);

        // Data untuk filter dropdown
        $engineers = Engineer::where('status', 'active')->get();
        $tools = Tool::all();

        return view('master.broken.brokenList', compact(
            'brokenTools',
            'viewResolved',
            'engineers',
            'tools'
        ));
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
                $tool->decrementQuantity($data['quantity']);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil disimpan',
                    'redirect' => route('forms.complete'),
                ]);
            }

            return redirect()
                ->route('forms.complete')
                ->with('success', 'Laporan alat rusak berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

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
                    $tool->incrementQuantity($data['quantity']);
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

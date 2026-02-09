<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Borrow\StoreBorrowRequest;
use App\Models\Borrow;
use App\Models\Engineer;
use App\Models\Tool;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewCompleted = request('is_completed') == 1;

        $query = Borrow::with(['engineer', 'borrowDetails' => function ($query) use ($viewCompleted) {
            // Jika viewCompleted false, hanya tampilkan borrow details yang belum complete
            if (! $viewCompleted) {
                $query->where('is_complete', 0);
            }
            $query->with('tool');
        }])->when($viewCompleted, function ($q) {
            return $q->where('is_completed', 1);
        }, function ($q) {
            return $q->where('is_completed', 0);
        });

        // Filter by engineer
        if (request('engineer_id')) {
            $query->where('engineer_id', request('engineer_id'));
        }

        // Filter by tool (via borrow details)
        if (request('tool_id')) {
            $query->whereHas('borrowDetails', function ($q) {
                $q->where('tool_id', request('tool_id'));
            });
        }

        // Filter by job reference
        if (request('job_reference')) {
            $query->where('job_reference', 'like', '%'.request('job_reference').'%');
        }

        // Filter by date range
        if (request('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        // Sorting
        switch (request('sort')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // newest
                $query->latest();
        }

        $borrows = $query->paginate(20);

        // Get data for filter dropdowns
        $engineers = Engineer::where('status', 'active')->get();
        $tools = Tool::all();

        return view('master.borrowList', compact(
            'borrows',
            'viewCompleted',
            'engineers',
            'tools'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function form()
    {
        $engineers = Engineer::where('status', 'active')->get();
        // Di controller
        $tools = Tool::all()->map(function ($tool) {
            return [
                'id' => $tool->id,
                'code' => $tool->code,
                'name' => $tool->name,
                'description' => $tool->description,
                'spec' => $tool->spec,
                'image' => $tool->image ? asset('storage/'.$tool->image) : null,
                'quantity' => $tool->quantity,
                'locator' => $tool->locator,
                'current_quantity' => $tool->current_quantity,
                'current_locator' => $tool->current_locator,
                'last_audited_at' => $tool->last_audited_at,
            ];
        });        // dd($tools->first()); // Uncomment to check

        return view('forms.borrow', compact('engineers', 'tools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('borrow', 'public');
            }

            // Validasi stok tools
            foreach ($data['details'] as $detail) {
                $tool = Tool::find($detail['tool_id']);
                if (! $tool) {
                    // Jika AJAX request, return JSON
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Tool tidak ditemukan',
                        ], 422);
                    }

                    return back()->with('error', 'Tool tidak ditemukan');
                }
            }

            $borrow = Borrow::create([
                'engineer_id' => $data['engineer_id'] ?? null,
                'job_reference' => $data['job_reference'],
                'is_completed' => 0, // Default value
                'image' => $data['image'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            // Create borrow details dan update current_quantity
            foreach ($data['details'] as $detail) {
                $borrow->borrowDetails()->create([
                    'tool_id' => $detail['tool_id'],
                    'quantity' => $detail['quantity'],
                ]);

                // Update current_quantity
                $tool = Tool::find($detail['tool_id']);
                $tool->decrementQuantity($detail['quantity']);
                $tool->save();
            }

            // Jika AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peminjaman berhasil disimpan',
                    'redirect' => route('forms.complete'),
                ]);
            }

            return redirect()
                ->route('forms.complete')
                ->with('success', 'Borrow record created successfully.');
        } catch (\Exception $e) {
            // Jika AJAX request, return JSON error
            if ($request->ajax()) {
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

    // BorrowController.php
    public function complete(string $id)
    {
        // Update status menjadi selesai
        $borrow = Borrow::findOrFail($id);

        $borrow->update([
            'is_completed' => 1,
            'completed_at' => now(), // Jika ada kolom returned_at
        ]);

        // Optional: Kembalikan stok tools
        foreach ($borrow->borrowDetails as $detail) {
            if ($detail->tool) {
                // Increment current_quantity saat dikembalikan
                $detail->tool->incrementQuantity($detail['quantity']);
            }
        }

        return back()->with('success', 'Peminjaman berhasil ditandai sebagai selesai.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

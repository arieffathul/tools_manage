<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Engineer;
use App\Models\Tool;
use Illuminate\Http\Request;

class BorrowReturnController extends Controller
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
        $borrow = Borrow::with(['engineer', 'borrowDetails.tool'])
            ->where('is_completed', 0)
            ->get();

        return view('forms.returnSelect', compact('borrow'));
    }

    public function form(Request $request)
    {
        $borrow = null;
        $borrowDetails = collect([]);

        // Jika ada borrow_id, ambil data peminjaman
        if ($request->has('borrow_id')) {
            $borrow = Borrow::with(['engineer', 'borrowDetails.tool'])
                ->find($request->borrow_id);

            if ($borrow && ! $borrow->is_completed) {
                // Format data tools dari borrow
                $borrowDetails = $borrow->borrowDetails->map(function ($detail) {
                    return [
                        'tool_id' => $detail->tool_id,
                        'tool_name' => $detail->tool->name,
                        'borrowed_quantity' => $detail->quantity,
                        'returned_quantity' => $detail->quantity, // Default kembalikan semua
                        'locator' => $detail->tool->current_locator ?? $detail->tool->locator,
                        'max_quantity' => $detail->quantity, // Untuk validasi max
                    ];
                });
            }
        }

        $engineers = Engineer::where('status', 'active')->get();
        $tools = Tool::all();

        // Format tools untuk JS
        $toolsFormatted = $tools->map(function ($tool) {
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
        });

        return view('forms.return', compact(
            'borrow',
            'engineers',
            'toolsFormatted',
            'borrowDetails'
        ));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

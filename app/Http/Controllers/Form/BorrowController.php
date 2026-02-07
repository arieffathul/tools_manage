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
        $viewIncomplete = request('is_completed') == 0;

        $borrows = Borrow::with(['engineer', 'details'])
            ->where('is_completed', $viewIncomplete ? 0 : 1)
            ->get();

        return view('master.borrows', compact('borrows', 'viewIncomplete'));
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

            $borrow = Borrow::create([
                'engineer_id' => $data['engineer_id'] ?? null,
                'job_reference' => $data['job_reference'],
                'is_completed' => $data['is_completed'] ?? 0,
                'image' => $data['image'] ?? null,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['details'] as $detail) {
                $borrow->borrowDetails()->create([ // Ganti details() menjadi borrowDetails()
                    'tool_id' => $detail['tool_id'],
                    'quantity' => $detail['quantity'],
                ]);
            }

            return redirect()
                ->route('complete')
                ->with('success', 'Borrow record created successfully.');
        } catch (\Exception $e) {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

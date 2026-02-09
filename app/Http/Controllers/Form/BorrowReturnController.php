<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Return\StoreReturnRequest;
use App\Models\Borrow;
use App\Models\BorrowReturn;
use App\Models\Engineer;
use App\Models\ReturnDetail;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $borrow = Borrow::with(['engineer', 'borrowDetails' => function ($query) {
            // Jika viewCompleted false, hanya tampilkan borrow details yang belum complete
            $query->where('is_complete', 0);
            $query->with('tool');
        }])
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
            $borrow = Borrow::with(['engineer', 'borrowDetails' => function ($query) {
                // Jika viewCompleted false, hanya tampilkan borrow details yang belum complete
                $query->where('is_complete', 0);
                $query->with('tool');
            }])
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
    public function store(StoreReturnRequest $request)
    {
        try {
            DB::beginTransaction();

            // Validasi untuk tanpa identitas (borrow_id null)
            if (! $request->borrow_id && empty($request->job_reference)) {
                return back()->with('error', 'Job reference wajib diisi untuk pengembalian tanpa identitas.')
                    ->withInput();
            }

            // Buat BorrowReturn
            $borrowReturn = BorrowReturn::create([
                'borrow_id' => $request->borrow_id, // null untuk tanpa identitas
                'returner_id' => $request->returner_id,
                'job_reference' => $request->job_reference,
                'notes' => $request->notes,
            ]);

            // Jika ada borrow_id, update borrow details
            if ($request->borrow_id) {
                $borrow = Borrow::with('borrowDetails')->find($request->borrow_id);

                if (! $borrow) {
                    throw new \Exception('Data peminjaman tidak ditemukan');
                }

                // Update quantity di borrow details untuk setiap tool yang dikembalikan
                foreach ($request->details as $returnDetail) {
                    $borrowDetail = $borrow->borrowDetails
                        ->where('tool_id', $returnDetail['tool_id'])
                        ->first();

                    $borrowDetail->decrementQuantity($returnDetail['quantity']);

                }

                // Cek apakah semua borrow details sudah complete
                $allComplete = $borrow->borrowDetails->every(function ($detail) {
                    return $detail->is_complete;
                });

                if ($allComplete) {
                    $borrow->update([
                        'is_completed' => 1,
                        'completed_at' => now(),
                    ]);
                }
            }

            // if ($request->borrow_id) {
            //     // Update status is_completed di Borrow jika semua BorrowDetails sudah complete
            //     $borrow = Borrow::with('borrowDetails')->find($request->borrow_id);
            //     foreach ($borrow->borrowDetails as $detail)
            //     $allComplete = $borrow->borrowDetails->every(function ($detail) {
            //         return $detail->is_complete;
            //     });

            //     if ($allComplete) {
            //         $borrow->is_completed = 1;
            //         $borrow->completed_at = now();
            //         $borrow->save();
            //     }
            // }

            // Proses setiap detail
            foreach ($request->details as $index => $detail) {
                // Handle image upload
                $imagePath = null;
                if (isset($detail['image']) && $detail['image']->isValid()) {
                    $imagePath = $detail['image']->store('return-details', 'public');
                }

                // Validasi required fields
                if (empty($detail['tool_id'])) {
                    throw new \Exception("Tool ID tidak valid untuk detail ke-{$index}");
                }

                if (empty($detail['quantity']) || $detail['quantity'] < 1) {
                    throw new \Exception("Quantity tidak valid untuk detail ke-{$index}");
                }

                if (empty($detail['locator'])) {
                    throw new \Exception("Locator wajib diisi untuk detail ke-{$index}");
                }

                // Buat ReturnDetail
                ReturnDetail::create([
                    'borrow_return_id' => $borrowReturn->id,
                    'tool_id' => $detail['tool_id'],
                    'quantity' => $detail['quantity'],
                    'image' => $imagePath,
                    'locator' => $detail['locator'],
                ]);

                // Increment stock tool
                $tool = Tool::find($detail['tool_id']);
                if ($tool) {
                    $tool->incrementQuantity($detail['quantity']);
                    // $tool->increment('current_quantity', $detail['quantity']);
                    $tool->current_locator = $detail['locator'];
                    $tool->save();
                }

            }

            DB::commit();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Pengembalian berhasil dicatat.',
            //     'redirect' => route('forms.complete'),
            // ]);
            return redirect()
                ->route('forms.complete')
                ->with('success', 'Pengembalian berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 422);
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

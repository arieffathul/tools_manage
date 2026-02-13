<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\StoreToolRequest;
use App\Http\Requests\Tool\UpdateToolRequest;
use App\Models\Tool;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tool::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tools = $query->paginate(10)->withQueryString();

        return view('master.tool.tools', compact('tools'));
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
    public function store(StoreToolRequest $request)
    {
        try {
            $data = $request->validated();

            // Auto-fill current_quantity and current_locator
            $data['current_quantity'] = $data['quantity'];
            $data['current_locator'] = $data['locator'];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('tool', 'public');
            }
            // Debug: Check before creating
            // dd($data);
            Tool::create($data);

            return redirect()
                ->route('tool.index')
                ->with('success', 'Tool berhasil ditambahkan');

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
            $tool = Tool::find($id);
            $response['success'] = true;
            $response['data'] = ['tool' => $tool];
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
        // $tool = Tool::findOrFail($id);

        // return view('master.tool.edit', compact('tool'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToolRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $tool = Tool::where('id', $id)->first();
            if ($tool) {
                $data = $request->validated();

                // $data['current_quantity'] = $data['quantity'];
                $data['current_locator'] = $data['locator'];

                if ($request->hasFile('image')) {
                    if ($tool->image) {
                        Storage::disk('public')->delete($tool->image);
                    }

                    $data['image'] = $request->file('image')
                        ->store('tool', 'public');
                }

                $tool->update($data);

                return redirect()
                    ->route('tool.index')
                    ->with('success', 'Tool berhasil diupdate');
            } else {
                return redirect()->route('tool.index')->with('error', 'Engineer tidak ditemukan.');
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
            $tool = Tool::find($id);
            // if ($tool->image) {
            //     Storage::disk('public')->delete('tool/'.$tool->image);
            // }

            // $tool->delete();
            // $response['success'] = true;
            // $response['data'] = ['tool' => $tool];
            if ($tool->image) {
                Storage::disk('public')->delete($tool->image);
            }

            $tool->delete();

            return redirect()
                ->route('tool.index')
                ->with('success', 'Tool berhasil dihapus');
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        // return response()->json($response);
        // return redirect()->route('admin.tool.index')->with('success', 'tool berhasil dihapus.');

    }
}

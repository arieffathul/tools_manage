<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\StoreToolRequest;
use App\Http\Requests\Tool\UpdateToolRequest;
use App\Models\Tool;
use Exception;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = Tool::all();

        return view('master.tool.index', compact('tools'));

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

            // $tool = new Tool;
            // $tool->code = $data['code'];
            // $tool->name = $data['name'];
            // $tool->description = $data['description'];
            // $tool->spec = $data['spec'];
            // $tool->quantity = $data['quantity'];
            // $tool->locator = $data['locator'];
            // $tool->current_quantity = $data['current_quantity'];
            // $tool->current_locator = $data['current_locator'];
            // $tool->last_audited_at = $data['last_audited_at'];
            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');
            //     $filename = time().'_'.$file->getClientOriginalName();
            //     // $file->storeAs('public/tool', $filename);
            //     Storage::disk('public')->putFileAs('tool', $file, $filename);

            //     // dd($path);
            //     $tool->image = $filename;
            // }

            // $tool->save();

            // return redirect()->route('master.tool.index')->with('success', 'tool berhasil ditambahkan.');
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')
                    ->store('tool', 'public');
            }

            Tool::create($data);

            return redirect()
                ->route('master.tool.index')
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
        $tool = Tool::findOrFail($id);

        return view('master.tool.edit', compact('tool'));

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
                // $tool->code = $data['code'];
                // $tool->name = $data['name'];
                // $tool->description = $data['description'];
                // $tool->spec = $data['spec'];
                // $tool->quantity = $data['quantity'];
                // $tool->locator = $data['locator'];
                // $tool->current_quantity = $data['current_quantity'];
                // $tool->current_locator = $data['current_locator'];
                // $tool->last_audited_at = $data['last_audited_at'];
                // if ($request->hasFile('image')) {
                //     $file = $request->file('image');
                //     $filename = time().'_'.$file->getClientOriginalName();
                //     // $file->storeAs('public/tool', $filename);
                //     Storage::disk('public')->putFileAs('tool', $file, $filename);

                //     // dd($path);
                //     $tool->image = $filename;
                // }

                // $tool->save();

                // return redirect()->route('master.tool.index')->with('success', 'Engineer berhasil diupdate.');
                $data = $request->validated();

                if ($request->hasFile('image')) {
                    if ($tool->image) {
                        Storage::disk('public')->delete($tool->image);
                    }

                    $data['image'] = $request->file('image')
                        ->store('tool', 'public');
                }

                $tool->update($data);

                return redirect()
                    ->route('master.tool.index')
                    ->with('success', 'Tool berhasil diupdate');
            } else {
                return redirect()->route('master.tool.index')->with('error', 'Engineer tidak ditemukan.');
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
                ->route('master.tool.index')
                ->with('success', 'Tool berhasil dihapus');
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        // return response()->json($response);
        return redirect()->route('admin.tool.index')->with('success', 'tool berhasil dihapus.');

    }
}

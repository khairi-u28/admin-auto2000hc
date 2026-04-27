<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Sesi;
use Illuminate\Http\Request;

class SesiController extends Controller
{
    public function index()
    {
        return response()->json(Sesi::all());
    }

    public function show($id)
    {
        return response()->json(Sesi::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        $sesi = Sesi::create($data);

        return response()->json($sesi, 201);
    }

    public function update(Request $request, $id)
    {
        $sesi = Sesi::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer',
        ]);

        $sesi->update($data);

        return response()->json($sesi);
    }

    public function destroy($id)
    {
        $sesi = Sesi::findOrFail($id);
        $sesi->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        return response()->json(Guru::with('kelas')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:gurus,nip',
            'mapel' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        $guru = Guru::create($validated);
        $guru->load('kelas');

        return response()->json($guru, 201);
    }

    public function show(string $id)
    {
        $guru = Guru::with('kelas')->findOrFail($id);

        return response()->json($guru);
    }

    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'nip' => 'sometimes|required|string|max:255|unique:gurus,nip,' . $guru->id,
            'mapel' => 'sometimes|required|string|max:255',
            'id_kelas' => 'sometimes|required|exists:kelas,id',
        ]);

        $guru->update($validated);
        $guru->load('kelas');

        return response()->json($guru);
    }

    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return response()->json(['message' => 'Guru dihapus']);
    }
}
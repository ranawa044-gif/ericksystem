<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('pengelola.mata-kuliah', [
            'mataKuliahs' => MataKuliah::query()
                ->orderBy('semester_default')
                ->orderBy('kode')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:mata_kuliahs,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester_default' => ['required', 'integer', 'min:1', 'max:14'],
        ]);

        MataKuliah::create($validated);

        return redirect()
            ->route('pengelola.mata-kuliah')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('pengelola.mata-kuliah-edit', [
            'mataKuliah' => $mataKuliah,
        ]);
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', Rule::unique('mata_kuliahs', 'kode')->ignore($mataKuliah->id)],
            'nama' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester_default' => ['required', 'integer', 'min:1', 'max:14'],
        ]);

        $mataKuliah->update($validated);

        return redirect()
            ->route('pengelola.mata-kuliah')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($mataKuliah->krs()->exists()) {
            return redirect()
                ->route('pengelola.mata-kuliah')
                ->withErrors('Mata kuliah tidak bisa dihapus karena sudah dipakai pada data KRS/nilai mahasiswa.');
        }

        $mataKuliah->delete();

        return redirect()
            ->route('pengelola.mata-kuliah')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}

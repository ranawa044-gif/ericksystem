<?php

namespace App\Http\Controllers;

use App\Models\CatatanDosenWali;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatanDosenWaliController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $mahasiswas = Mahasiswa::query()
            ->with('dosenWali')
            ->when($user->role === 'dosen', function ($query) use ($user) {
                $query->where('dosen_wali_id', $user->dosen_id);
            })
            ->orderBy('nama')
            ->get();

        $catatanList = CatatanDosenWali::query()
            ->with(['mahasiswa', 'dosen'])
            ->when($user->role === 'dosen', function ($query) use ($user) {
                $query->whereHas('mahasiswa', function ($q) use ($user) {
                    $q->where('dosen_wali_id', $user->dosen_id);
                });
            })
            ->latest('tanggal')
            ->get();

        return view('pengelola.catatan', [
            'mahasiswas' => $mahasiswas,
            'catatanList' => $catatanList,
        ]);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['required', 'string', 'max:2000'],
        ]);

        $mahasiswa = Mahasiswa::query()->findOrFail($validated['mahasiswa_id']);
        $dosenId = $user->dosen_id ?: $mahasiswa->dosen_wali_id;

        CatatanDosenWali::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosenId,
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'],
        ]);

        return redirect()
            ->route('pengelola.catatan')
            ->with('success', 'Catatan dosen wali berhasil disimpan.');
    }
}

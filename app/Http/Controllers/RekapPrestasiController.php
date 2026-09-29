<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapPrestasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $dosens = Dosen::query()->orderBy('nama')->get();
        $dosenId = $user->role === 'admin'
            ? $request->query('dosen_id')
            : $user->dosen_id;

        $prestasi = PrestasiMahasiswa::query()
            ->with(['mahasiswa.dosenWali'])
            ->whereHas('mahasiswa', function ($query) use ($dosenId) {
                if ($dosenId) {
                    $query->where('dosen_wali_id', $dosenId);
                }
            })
            ->latest('tanggal')
            ->latest('created_at')
            ->get();

        $grouped = $prestasi->groupBy(fn ($item) => $item->mahasiswa->dosenWali?->id ?? 0);

        return view('pengelola.rekap-prestasi', [
            'dosens' => $dosens,
            'dosenId' => $dosenId,
            'prestasi' => $prestasi,
            'grouped' => $grouped,
        ]);
    }
}

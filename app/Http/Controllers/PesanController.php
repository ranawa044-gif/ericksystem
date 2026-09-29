<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PesanMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    // Tampil pesan untuk mahasiswa (form kirim + list pesan yang sudah dikirim)
    public function indexMahasiswa(Request $request, string $nim)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $user->mahasiswa?->nim !== $nim) {
            abort(403);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        $pesan = $mahasiswa->pesanKeMahasiswa()
            ->with('dosen')
            ->get();

        return view('pesan.mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'pesan' => $pesan,
        ]);
    }

    // Simpan pesan dari mahasiswa
    public function store(Request $request, string $nim)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $user->mahasiswa?->nim !== $nim) {
            abort(403);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'pesan' => 'required|string|min:5|max:1000',
            'tipe' => 'required|in:umum,saran,kritik,pertanyaan',
        ]);

        PesanMahasiswa::create([
            'mahasiswa_id' => $mahasiswa->id,
            'pesan' => $validated['pesan'],
            'tipe' => $validated['tipe'],
        ]);

        return redirect()->route('pesan.mahasiswa', $nim)
            ->with('success', 'Pesan berhasil dikirim ke dosen wali');
    }

    // Tampil pesan untuk dosen wali
    public function indexDosen(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'dosen') {
            abort(403);
        }

        $dosen = $user->dosen;
        
        $q = $request->query('q');
        $tipe = $request->query('tipe');
        $tanggal = $request->query('tanggal');

        // Ambil pesan dari semua mahasiswa yang punya dosen wali ini
        $pesan = PesanMahasiswa::query()
            ->whereHas('mahasiswa', fn ($query) => $query->where('dosen_wali_id', $dosen->id))
            ->when($q, function ($query) use ($q) {
                $query->whereHas('mahasiswa', function ($query) use ($q) {
                    $query->where('nama', 'like', "%{$q}%")
                          ->orWhere('nim', 'like', "%{$q}%");
                });
            })
            ->when($tipe, function ($query) use ($tipe) {
                $query->where('tipe', $tipe);
            })
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('created_at', $tanggal);
            })
            ->with(['mahasiswa', 'dosen'])
            ->latest('created_at')
            ->paginate(30);

        return view('pesan.dosen', [
            'pesan' => $pesan,
        ]);
    }

    public function balasDosen(Request $request, PesanMahasiswa $pesan)
    {
        $user = Auth::user();

        if ($user->role !== 'dosen') {
            abort(403);
        }

        $pesan->load('mahasiswa');

        if ($pesan->mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }

        $validated = $request->validate([
            'balasan_dosen' => 'required|string|min:5|max:1000',
        ]);

        $pesan->update([
            'dosen_id' => $user->dosen_id,
            'balasan_dosen' => $validated['balasan_dosen'],
            'dibalas_at' => now(),
        ]);

        return redirect()->route('pesan.dosen')
            ->with('success', 'Balasan berhasil dikirim ke mahasiswa');
    }

    public function indexAdmin(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $dosenId = $request->query('dosen_id');
        $dosens = Dosen::query()->orderBy('nama')->get();

        $pesan = PesanMahasiswa::query()
            ->with(['mahasiswa.dosenWali', 'dosen'])
            ->when($dosenId, function ($query) use ($dosenId) {
                $query->whereHas('mahasiswa', function ($q) use ($dosenId) {
                    $q->where('dosen_wali_id', $dosenId);
                });
            })
            ->latest('created_at')
            ->get();

        return view('pengelola.pesan-mahasiswa', [
            'pesan' => $pesan,
            'dosens' => $dosens,
            'dosenId' => $dosenId,
        ]);
    }
}

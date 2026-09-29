<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestasiMahasiswaController extends Controller
{
    public function index(string $nim)
    {
        $mahasiswa = Mahasiswa::query()
            ->with(['dosenWali', 'prestasi'])
            ->where('nim', $nim)
            ->firstOrFail();

        $this->authorizeAccess($mahasiswa);

        return view('prestasi.mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'prestasi' => $mahasiswa->prestasi,
        ]);
    }

    public function store(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        $this->authorizeOwner($mahasiswa);

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'jenis_kegiatan' => ['nullable', 'string', 'max:100'],
            'tempat' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
            'pembimbing' => ['nullable', 'string', 'max:255'],
            'bukti_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('bukti_file')) {
            $directory = public_path('uploads/prestasi');

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('bukti_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $validated['bukti_file'] = $filename;
        }

        $mahasiswa->prestasi()->create($validated);

        return redirect()->route('mahasiswa.prestasi', $mahasiswa->nim)
            ->with('success', 'Prestasi atau kegiatan berhasil ditambahkan.');
    }

    public function destroy(string $nim, PrestasiMahasiswa $prestasi)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        $this->authorizeOwner($mahasiswa);

        if ($prestasi->mahasiswa_id !== $mahasiswa->id) {
            abort(404);
        }

        if ($prestasi->bukti_file) {
            $path = public_path('uploads/prestasi/' . $prestasi->bukti_file);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $prestasi->delete();

        return redirect()->route('mahasiswa.prestasi', $mahasiswa->nim)
            ->with('success', 'Data prestasi berhasil dihapus.');
    }

    private function authorizeAccess(Mahasiswa $mahasiswa): void
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $user->mahasiswa?->id !== $mahasiswa->id) {
            abort(403);
        }

        if ($user->role === 'dosen' && $mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }
    }

    private function authorizeOwner(Mahasiswa $mahasiswa): void
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa' || $user->mahasiswa?->id !== $mahasiswa->id) {
            abort(403);
        }
    }
}

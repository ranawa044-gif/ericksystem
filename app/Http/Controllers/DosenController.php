<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with('mahasiswaBimbingan')
            ->orderBy('nama')
            ->get();

        return view('pengelola.dosen', [
            'dosens' => $dosens,
        ]);
    }

    public function create()
    {
        return view('pengelola.dosen-form', [
            'dosen' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|unique:dosens,nidn',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $dosen = Dosen::create([
            'nidn' => $validated['nidn'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
        ]);

        User::create([
            'name' => $dosen->nama,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
            'mahasiswa_id' => null,
            'dosen_id' => $dosen->id,
        ]);

        return redirect()->route('pengelola.dosen')
            ->with('success', 'Dosen berhasil ditambahkan dan akun login dibuat.');
    }

    public function edit(Dosen $dosen)
    {
        return view('pengelola.dosen-form', [
            'dosen' => $dosen,
        ]);
    }

    public function update(Request $request, Dosen $dosen)
    {
        $userDosen = $dosen->user;

        $validated = $request->validate([
            'nidn' => 'required|string|unique:dosens,nidn,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($userDosen?->id ?? 'NULL'),
            'password' => 'nullable|string|min:6',
        ]);

        $dosen->update([
            'nidn' => $validated['nidn'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
        ]);

        $userData = [
            'name' => $dosen->nama,
            'email' => $validated['email'],
            'role' => 'dosen',
            'mahasiswa_id' => null,
            'dosen_id' => $dosen->id,
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        User::updateOrCreate(
            ['dosen_id' => $dosen->id],
            $userData + ['password' => $userDosen?->password ?? Hash::make($validated['password'] ?? 'password')]
        );

        return redirect()->route('pengelola.dosen')
            ->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        // Cek apakah dosen memiliki mahasiswa wali
        if ($dosen->mahasiswaBimbingan()->exists()) {
            return redirect()->route('pengelola.dosen')
                ->with('error', 'Dosen tidak bisa dihapus karena masih membimbing mahasiswa');
        }

        // Hapus akun login dosen jika ada
        if ($dosen->user) {
            $dosen->user->delete();
        }

        $dosen->delete();

        return redirect()->route('pengelola.dosen')
            ->with('success', 'Dosen berhasil dihapus');
    }
}

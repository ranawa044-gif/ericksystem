<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $query = Mahasiswa::query()->with('dosenWali');

        if ($user->role === 'dosen') {
            $query->where('dosen_wali_id', $user->dosen_id);
        } else {
            if ($request->filled('dosen_wali_id')) {
                $query->where('dosen_wali_id', $request->dosen_wali_id);
            }
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('program_studi')) {
            $query->where('program_studi', $request->program_studi);
        }

        $mahasiswas = $query->orderBy('nim')->paginate(35)->withQueryString();

        $angkatans = Mahasiswa::query()->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $programStudis = Mahasiswa::query()->select('program_studi')->distinct()->orderBy('program_studi')->pluck('program_studi');

        return view('pengelola.mahasiswa', [
            'mahasiswas' => $mahasiswas,
            'dosens' => Dosen::query()->orderBy('nama')->get(),
            'angkatans' => $angkatans,
            'programStudis' => $programStudis,
        ]);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:30', 'unique:mahasiswas,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'program_studi' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2100'],
            'dosen_wali_id' => ['required', 'exists:dosens,id'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath = $filename;
        }

        $mahasiswa = Mahasiswa::create([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'program_studi' => $validated['program_studi'],
            'angkatan' => $validated['angkatan'],
            'dosen_wali_id' => $validated['dosen_wali_id'],
            'foto' => $fotoPath,
        ]);

        $email = $validated['email'] ?: strtolower($validated['nim']) . '@student.local';
        $password = $validated['password'] ?: 'password';

        User::create([
            'name' => $mahasiswa->nama,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'mahasiswa',
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => null,
        ]);

        return redirect()
            ->route('pengelola.mahasiswa')
            ->with('success', "Mahasiswa berhasil ditambahkan. Akun login: {$email} / {$password}");
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        return view('pengelola.mahasiswa-edit', [
            'mahasiswa' => $mahasiswa->load('dosenWali'),
            'dosens' => Dosen::query()->orderBy('nama')->get(),
            'userMahasiswa' => User::query()->where('mahasiswa_id', $mahasiswa->id)->first(),
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $userMahasiswa = User::query()->where('mahasiswa_id', $mahasiswa->id)->first();

        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:30', Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->id)],
            'nama' => ['required', 'string', 'max:255'],
            'program_studi' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2100'],
            'dosen_wali_id' => ['required', 'exists:dosens,id'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userMahasiswa?->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $fotoPath = $mahasiswa->foto;
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && file_exists(public_path('uploads/' . $mahasiswa->foto))) {
                unlink(public_path('uploads/' . $mahasiswa->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $fotoPath = $filename;
        }

        $mahasiswa->update([
            'nim' => $validated['nim'],
            'nama' => $validated['nama'],
            'program_studi' => $validated['program_studi'],
            'angkatan' => $validated['angkatan'],
            'dosen_wali_id' => $validated['dosen_wali_id'],
            'foto' => $fotoPath,
        ]);

        $email = $validated['email'] ?: strtolower($validated['nim']) . '@student.local';

        $userData = [
            'name' => $mahasiswa->nama,
            'email' => $email,
            'role' => 'mahasiswa',
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => null,
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        User::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            $userData + ['password' => $userMahasiswa?->password ?: Hash::make('password')]
        );

        return redirect()
            ->route('pengelola.mahasiswa')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }
}

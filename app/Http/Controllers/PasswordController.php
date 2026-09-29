<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        return view('auth.ganti-password');
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $validated = $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'confirmed', Password::min(6)],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        if (! Hash::check($validated['password_lama'], $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['password_baru']);
        $user->save();

        if ($request->hasFile('foto')) {
            $mahasiswa = $user->mahasiswa;
            if ($mahasiswa->foto && file_exists(public_path('uploads/' . $mahasiswa->foto))) {
                unlink(public_path('uploads/' . $mahasiswa->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);

            $mahasiswa->update(['foto' => $filename]);
        }

        return redirect()
            ->route('dashboard.mahasiswa', ['nim' => $user->mahasiswa->nim])
            ->with('success', 'Password berhasil diganti.');
    }

    public function updateFoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        $mahasiswa = $user->mahasiswa;
        if ($mahasiswa->foto && file_exists(public_path('uploads/' . $mahasiswa->foto))) {
            unlink(public_path('uploads/' . $mahasiswa->foto));
        }

        $file = $request->file('foto');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        $mahasiswa->update(['foto' => $filename]);

        return redirect()
            ->route('mahasiswa.password.edit')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
    public function editDosen()
    {
        $user = Auth::user();

        if ($user->role !== 'dosen') {
            abort(403);
        }

        return view('auth.ganti-password-dosen');
    }

    public function updateDosen(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'dosen') {
            abort(403);
        }

        $validated = $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'confirmed', Password::min(6)],
        ]);

        if (! Hash::check($validated['password_lama'], $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['password_baru']);
        $user->save();

        return redirect()
            ->route('home')
            ->with('success', 'Password berhasil diganti.');
    }
}

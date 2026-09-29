<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputNilaiController extends Controller
{
    public function index(Request $request, ?Krs $editKrs = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $search = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50], true) ? $perPage : 10;

        $nilaiQuery = Krs::query()
            ->with(['mahasiswa', 'mataKuliah', 'semester', 'nilai'])
            ->whereHas('nilai')
            ->where('status', 'diambil')
            ->when($user->role === 'dosen', function ($query) use ($user) {
                $query->whereHas('mahasiswa', function ($mahasiswaQuery) use ($user) {
                    $mahasiswaQuery->where('dosen_wali_id', $user->dosen_id);
                });
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                            $mahasiswaQuery
                                ->where('nim', 'like', "%{$search}%")
                                ->orWhere('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('mataKuliah', function ($mataKuliahQuery) use ($search) {
                            $mataKuliahQuery
                                ->where('kode', 'like', "%{$search}%")
                                ->orWhere('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('updated_at');

        $nilaiList = $nilaiQuery
            ->paginate($perPage)
            ->withQueryString();

        return view('dosen.input-nilai', [
            'mahasiswas' => Mahasiswa::query()
                ->when($user->role === 'dosen', function ($query) use ($user) {
                    $query->where('dosen_wali_id', $user->dosen_id);
                })
                ->orderBy('nim')
                ->get(),
            'mataKuliahs' => MataKuliah::query()->orderBy('semester_default')->orderBy('kode')->get(),
            'semesters' => Semester::query()->orderBy('urutan')->get(),
            'nilaiList' => $nilaiList,
            'rangeNilai' => Nilai::RANGE_NILAI,
            'editKrs' => $editKrs?->load(['mahasiswa', 'mataKuliah', 'semester', 'nilai']),
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function edit(Krs $krs)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        if (! $krs->nilai) {
            abort(404);
        }

        if ($user->role === 'dosen' && $krs->mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }

        return $this->index(request(), $krs);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $validated = $request->validate([
            'nim' => ['required', 'exists:mahasiswas,nim'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'nilai_angka' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $mahasiswa = Mahasiswa::query()
            ->where('nim', $validated['nim'])
            ->firstOrFail();

        if ($user->role === 'dosen' && $mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }

        $krs = Krs::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'mata_kuliah_id' => $validated['mata_kuliah_id'],
                'semester_id' => $validated['semester_id'],
            ],
            ['status' => 'diambil']
        );

        $nilaiAngka = round((float) $validated['nilai_angka'], 2);
        $hasilKonversi = Nilai::konversiDariAngka($nilaiAngka);

        Nilai::updateOrCreate(
            ['krs_id' => $krs->id],
            [
                'nilai_angka' => $nilaiAngka,
                'nilai_huruf' => $hasilKonversi['nilai_huruf'],
                'bobot' => $hasilKonversi['bobot'],
                'status_lulus' => $hasilKonversi['status_lulus'],
            ]
        );

        return redirect()
            ->route('pengelola.input-nilai')
            ->with('success', 'Nilai berhasil disimpan dan dikonversi otomatis.');
    }

    public function update(Request $request, Krs $krs)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isPengelola()) {
            abort(403);
        }

        $validated = $request->validate([
            'nim' => ['required', 'exists:mahasiswas,nim'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'nilai_angka' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $mahasiswa = Mahasiswa::query()
            ->where('nim', $validated['nim'])
            ->firstOrFail();

        if ($user->role === 'dosen' && $krs->mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }

        if ($user->role === 'dosen' && $mahasiswa->dosen_wali_id !== $user->dosen_id) {
            abort(403);
        }

        $targetKrs = Krs::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'mata_kuliah_id' => $validated['mata_kuliah_id'],
                'semester_id' => $validated['semester_id'],
            ],
            ['status' => 'diambil']
        );

        if ($targetKrs->id !== $krs->id && $krs->nilai) {
            $krs->nilai()->delete();
        }

        $nilaiAngka = round((float) $validated['nilai_angka'], 2);
        $hasilKonversi = Nilai::konversiDariAngka($nilaiAngka);

        Nilai::updateOrCreate(
            ['krs_id' => $targetKrs->id],
            [
                'nilai_angka' => $nilaiAngka,
                'nilai_huruf' => $hasilKonversi['nilai_huruf'],
                'bobot' => $hasilKonversi['bobot'],
                'status_lulus' => $hasilKonversi['status_lulus'],
            ]
        );

        return redirect()
            ->route('pengelola.input-nilai')
            ->with('success', 'Nilai berhasil diperbarui.');
    }
}

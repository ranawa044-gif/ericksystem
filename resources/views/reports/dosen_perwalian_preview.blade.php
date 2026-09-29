@extends('layouts.app')

@section('title', 'Preview Laporan Perwalian')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2 text-gray-800">Preview Laporan Perwalian</h1>
    <p class="text-muted">Lengkapi data administratif di bawah ini sebelum mengekspor Laporan Perwalian Resmi.</p>
</div>

<form id="perwalian-form" action="{{ route('reports.dosen_perwalian.pdf') }}" method="POST" target="_blank" class="card">
    @csrf
    
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Kolom Kiri -->
        <div>
            <h3 class="font-semibold text-lg border-b pb-2 mb-4">1. Identitas Perwalian</h3>
            
            <div class="mb-3">
                <label class="font-medium text-sm">Nama Dosen Wali</label>
                <input type="text" name="nama_dosen" class="form-control mt-1" value="{{ $dosen->nama }}" readonly style="background: #f1f5f9;">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Tahun Akademik</label>
                <input type="text" name="tahun_akademik" class="form-control mt-1" value="{{ $activeSemester->tahun_akademik ?? '' }}">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Semester Aktif</label>
                <input type="text" name="semester" class="form-control mt-1" value="{{ $activeSemester->nama ?? '' }}">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Jenis Perwalian</label>
                <select name="jenis_perwalian" class="form-control mt-1">
                    <option value="Awal Semester">Awal Semester</option>
                    <option value="Tengah Semester">Tengah Semester</option>
                    <option value="Akhir Semester">Akhir Semester</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Kelas</label>
                <input type="text" name="kelas" class="form-control mt-1" placeholder="Contoh: TRPL 2B">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Tingkat</label>
                <input type="text" name="tingkat" class="form-control mt-1" placeholder="Contoh: Tingkat 2">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Program Studi</label>
                <input type="text" name="program_studi" class="form-control mt-1" value="Sistem Informasi">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Jurusan</label>
                <input type="text" name="jurusan" class="form-control mt-1" value="Teknologi Informasi dan Komputer">
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div>
            <h3 class="font-semibold text-lg border-b pb-2 mb-4">2. Data Mahasiswa & Status</h3>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="mb-3">
                    <label class="font-medium text-sm">Total Mhs Aktif</label>
                    <input type="number" name="jumlah_aktif" class="form-control mt-1" value="{{ $mahasiswaCount }}">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Total Mhs Cuti</label>
                    <input type="number" name="jumlah_cuti" class="form-control mt-1" value="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Mhs Keluar</label>
                    <input type="number" name="jumlah_keluar" class="form-control mt-1" value="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Mhs KIP</label>
                    <input type="number" name="jumlah_kip" class="form-control mt-1" value="0">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="font-medium text-sm">Daftar Nama Mahasiswa KIP (Pisahkan baris baru)</label>
                <textarea name="nama_kip" class="form-control mt-1" rows="3" placeholder="- Budi Santoso&#10;- Siti Aminah"></textarea>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                <div class="mb-3">
                    <label class="font-medium text-sm">Sudah Perwalian</label>
                    <input type="number" name="sudah_perwalian" class="form-control mt-1" value="{{ $sudahPerwalian }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm">Belum Perwalian</label>
                    <input type="number" name="belum_perwalian" class="form-control mt-1" value="{{ $belumPerwalian }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm text-red-600">SP 1 </label>
                    <input type="number" name="sp1" class="form-control mt-1" value="{{ $sp1 }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm text-red-600">SP 2 </label>
                    <input type="number" name="sp2" class="form-control mt-1" value="{{ $sp2 }}" min="0">
                </div>
                <div class="mb-3">
                    <label class="font-medium text-sm text-red-600">SP 3 </label>
                    <input type="number" name="sp3" class="form-control mt-1" value="{{ $sp3 }}" min="0">
                </div>
            </div>
        </div>
    </div>

    <!-- Lebar Penuh -->
    <div class="mt-6">
        <h3 class="font-semibold text-lg border-b pb-2 mb-4">3. Mahasiswa Perlu Perhatian</h3>
        <div class="mb-4">
            <p class="text-xs text-muted mb-2">Tulis nama mahasiswa beserta alasan/catatannya (Pisahkan dengan baris baru)</p>
            <textarea name="mahasiswa_perhatian" class="form-control mt-1" rows="5" placeholder="- Ahmad Dani (NIM 12345): Sering bolos kuliah&#10;- Budi Santoso (NIM 67890): IPK turun drastis"></textarea>
        </div>

        <h3 class="font-semibold text-lg border-b pb-2 mb-4 mt-8">4. Prestasi Mahasiswa</h3>
        <div class="mb-3">
            <label class="font-medium text-sm">Jumlah Prestasi Periode Ini</label>
            <input type="number" name="jumlah_prestasi" class="form-control mt-1" value="0" min="0">
        </div>
        <div class="mb-4">
            <label class="font-medium text-sm">Prestasi Akademik</label>
            <p class="text-xs text-muted mb-1">Daftar prestasi akademik mahasiswa (Pisahkan baris baru)</p>
            <textarea name="prestasi_akademik" class="form-control mt-1" rows="3" placeholder="- Budi Santoso - Juara 1 Lomba Web Design - Nasional"></textarea>
        </div>
        <div class="mb-4">
            <label class="font-medium text-sm">Prestasi Non-Akademik</label>
            <p class="text-xs text-muted mb-1">Daftar prestasi non-akademik mahasiswa (Pisahkan baris baru)</p>
            <textarea name="prestasi_non_akademik" class="form-control mt-1" rows="3" placeholder="- Siti Aminah - Juara 2 Lomba Puisi - Universitas"></textarea>
        </div>

        <h3 class="font-semibold text-lg border-b pb-2 mb-4 mt-8">5. Catatan & Pengembangan</h3>
        
        <div class="mb-4">
            <label class="font-medium text-sm">Catatan Perwalian</label>
            <p class="text-xs text-muted mb-1">Gambaran singkat permasalahan yang muncul dalam perwalian.</p>
            <textarea name="catatan_perwalian" class="form-control mt-1" rows="4" placeholder="- Mahasiswa mengeluhkan fasilitas lab..."></textarea>
        </div>

        <div class="mb-4">
            <label class="font-medium text-sm">Pengembangan Diri Mahasiswa</label>
            <p class="text-xs text-muted mb-1">Kegiatan pengembangan diri yang diikuti mahasiswa (UKM, Organisasi, Kompetisi).</p>
            <textarea name="pengembangan_diri" class="form-control mt-1" rows="4" placeholder="- Sebagian besar mahasiswa aktif di UKM..."></textarea>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="mb-3">
                <label class="font-medium text-sm">Jadwal Perwalian Berikutnya</label>
                <input type="date" name="jadwal_berikutnya" class="form-control mt-1">
            </div>
            <div class="mb-3">
                <label class="font-medium text-sm">Nama Koordinator Program Studi (Kaprodi)</label>
                <input type="text" name="nama_kaprodi" class="form-control mt-1" placeholder="Nama dan Gelar Kaprodi">
            </div>
        </div>
    </div>

    <div class="mt-8 pt-4 border-t flex justify-end gap-3 items-center">
        <span id="draft-status" class="text-sm font-medium text-green-600 hidden mr-2"></span>
        <button type="button" id="btn-save-draft" class="btn" style="background: #e2e8f0; color: #334155;">Simpan Draft</button>
        <a href="{{ route('reports.index') }}" class="btn" style="background: #f1f5f9; color: #334155;">Batal</a>
        <button type="submit" class="btn btn-primary">Generate Laporan Resmi (PDF)</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('perwalian-form');
    const btnSaveDraft = document.getElementById('btn-save-draft');
    const draftStatus = document.getElementById('draft-status');
    const draftKey = 'perwalian_draft_{{ auth()->id() }}';

    // Load draft
    const savedDraft = localStorage.getItem(draftKey);
    if (savedDraft) {
        try {
            const data = JSON.parse(savedDraft);
            for (const key in data) {
                const field = form.elements[key];
                if (field && !field.readOnly && field.type !== 'hidden') {
                    field.value = data[key];
                }
            }
            draftStatus.classList.remove('hidden');
            draftStatus.textContent = 'Draft terakhir dimuat';
            setTimeout(() => draftStatus.classList.add('hidden'), 4000);
        } catch(e) {}
    }

    // Save draft function
    function saveDraft() {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key !== '_token') {
                data[key] = value;
            }
        }
        localStorage.setItem(draftKey, JSON.stringify(data));
        
        draftStatus.classList.remove('hidden');
        draftStatus.textContent = 'Draft tersimpan (' + new Date().toLocaleTimeString() + ')';
        setTimeout(() => draftStatus.classList.add('hidden'), 3000);
    }

    // Save on button click
    btnSaveDraft.addEventListener('click', saveDraft);

    // Auto save draft before window unload
    window.addEventListener('beforeunload', saveDraft);
});
</script>
@endsection

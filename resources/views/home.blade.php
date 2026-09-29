@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

@if(auth()->user()->role === 'dosen')
    @php
        $dosen = auth()->user()->dosen;
        $mahasiswas = \App\Models\Mahasiswa::with(['krs.mataKuliah', 'krs.nilai'])->where('dosen_wali_id', $dosen->id)->get();
        $totalMahasiswa = $mahasiswas->count();
        
        $totalKonsultasi = \App\Models\PesanMahasiswa::whereHas('mahasiswa', function($q) use ($dosen) {
            $q->where('dosen_wali_id', $dosen->id);
        })->count();
        
        $totalPrestasi = \App\Models\PrestasiMahasiswa::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))->count();
        
        // --- Calculate Academic Status ---
        $statusBaik = 0; // IPK >= 3.0
        $statusPerhatian = 0; // 2.0 <= IPK < 3.0
        $statusKonsultasi = 0; // IPK < 2.0
        
        foreach ($mahasiswas as $m) {
            $totalSks = 0;
            $totalBobot = 0;
            
            foreach ($m->krs as $krs) {
                if ($krs->nilai && $krs->mataKuliah) {
                    $totalSks += $krs->mataKuliah->sks;
                    $totalBobot += ($krs->nilai->bobot * $krs->mataKuliah->sks);
                }
            }
            
            $ipk = $totalSks > 0 ? $totalBobot / $totalSks : 0;
            
            if ($totalSks > 0) {
                if ($ipk >= 3.00) {
                    $statusBaik++;
                } elseif ($ipk >= 2.00) {
                    $statusPerhatian++;
                } else {
                    $statusKonsultasi++;
                }
            } else {
                $statusBaik++; // Default aman untuk mahasiswa baru
            }
        }
        
        $totalStatus = $statusBaik + $statusPerhatian + $statusKonsultasi;
        $pctBaik = $totalStatus > 0 ? round(($statusBaik / $totalStatus) * 100) : 0;
        $pctPerhatian = $totalStatus > 0 ? round(($statusPerhatian / $totalStatus) * 100) : 0;
        $pctKonsultasi = $totalStatus > 0 ? round(($statusKonsultasi / $totalStatus) * 100) : 0;
    @endphp

    <div class="mb-4">
        <h1 class="text-2xl font-semibold mb-2">Selamat datang, {{ $dosen->nama }}</h1>
        <p class="text-muted">Berikut adalah ringkasan akademik dari mahasiswa perwalian Anda.</p>
    </div>

    <div class="grid-4 mb-4">
        <div class="card" style="margin-bottom: 0;">
            <div class="text-muted text-sm font-semibold uppercase mb-2">Total Mahasiswa Wali</div>
            <div class="text-2xl font-semibold text-primary">{{ $totalMahasiswa }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div class="text-muted text-sm font-semibold uppercase mb-2">Mahasiswa Aktif</div>
            <div class="text-2xl font-semibold text-success">{{ $totalMahasiswa }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div class="text-muted text-sm font-semibold uppercase mb-2">Total Konsultasi</div>
            <div class="text-2xl font-semibold text-warning">{{ $totalKonsultasi }}</div>
        </div>
        <div class="card" style="margin-bottom: 0;">
            <div class="text-muted text-sm font-semibold uppercase mb-2">Total Prestasi</div>
            <div class="text-2xl font-semibold" style="color: #8b5cf6;">{{ $totalPrestasi }}</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <h3 class="font-semibold mb-4">Distribusi Status Akademik</h3>
            <div style="height: 20px; width: 100%; display: flex; border-radius: var(--radius-full); overflow: hidden; margin-bottom: 1rem;">
                <div style="width: {{ $pctBaik }}%; background: var(--success);" title="Aman"></div>
                <div style="width: {{ $pctPerhatian }}%; background: var(--warning);" title="Perlu Perhatian"></div>
                <div style="width: {{ $pctKonsultasi }}%; background: var(--danger);" title="Kritis"></div>
            </div>
            <div class="flex justify-between text-sm text-muted">
                <div class="flex items-center gap-2"><span style="width: 10px; height: 10px; background: var(--success); border-radius: 50%;"></span> Baik ({{ $pctBaik }}%)</div>
                <div class="flex items-center gap-2"><span style="width: 10px; height: 10px; background: var(--warning); border-radius: 50%;"></span> Perhatian ({{ $pctPerhatian }}%)</div>
                <div class="flex items-center gap-2"><span style="width: 10px; height: 10px; background: var(--danger); border-radius: 50%;"></span> Konsultasi ({{ $pctKonsultasi }}%)</div>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold mb-4">Akses Cepat</h3>
            <div class="grid-2">
                <a href="{{ route('pengelola.mahasiswa') }}" class="btn btn-secondary justify-between">
                    Data Mahasiswa
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
                <a href="{{ route('pesan.dosen') }}" class="btn btn-secondary justify-between">
                    Pesan Mahasiswa
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </div>

@elseif(auth()->user()->role === 'admin')
    @php
        // --- BASIC STATS ---
        $totalMahasiswa = \App\Models\Mahasiswa::count();
        $totalDosen = \App\Models\User::where('role', 'dosen')->count();
        $totalNilai = \App\Models\Nilai::count();
        $totalPrestasi = \App\Models\PrestasiMahasiswa::count();
        $totalKonsultasi = \App\Models\PesanMahasiswa::count();
        
        // --- SEMESTER AKTIF ---
        $activeSemester = \App\Models\Semester::orderBy('urutan', 'desc')->first();
        $semesterName = $activeSemester ? $activeSemester->nama . ' (' . $activeSemester->tahun_akademik . ')' : 'Belum Ada';
        
        // --- INSIGHT COUNTS ---
        $mahasiswaTanpaKonsultasi = \App\Models\Mahasiswa::doesntHave('pesanKeMahasiswa')->count();
        $mahasiswaBerprestasi = \App\Models\Mahasiswa::has('prestasi')->count();
        
        // --- ACADEMIC DISTRIBUTION ---
        $mahasiswas = \App\Models\Mahasiswa::with(['krs.mataKuliah', 'krs.nilai'])->get();
        
        $ipkSangatBaik = 0;
        $ipkBaik = 0;
        $ipkPerhatian = 0;
        
        $ipsTren = [];
        
        foreach ($mahasiswas as $m) {
            $totalSks = 0;
            $totalBobot = 0;
            
            foreach ($m->krs as $krs) {
                if ($krs->nilai && $krs->mataKuliah) {
                    $totalSks += $krs->mataKuliah->sks;
                    $totalBobot += ($krs->nilai->bobot * $krs->mataKuliah->sks);
                    
                    $semId = $krs->semester_id;
                    if (!isset($ipsTren[$semId])) {
                        $ipsTren[$semId] = ['sks' => 0, 'bobot' => 0];
                    }
                    $ipsTren[$semId]['sks'] += $krs->mataKuliah->sks;
                    $ipsTren[$semId]['bobot'] += ($krs->nilai->bobot * $krs->mataKuliah->sks);
                }
            }
            
            $ipk = $totalSks > 0 ? $totalBobot / $totalSks : 0;
            
            if ($totalSks > 0) {
                if ($ipk >= 3.50) {
                    $ipkSangatBaik++;
                } elseif ($ipk >= 3.00) {
                    $ipkBaik++;
                } else {
                    $ipkPerhatian++;
                }
            }
        }
        
        // Finalize IPS Data
        $semesters = \App\Models\Semester::orderBy('urutan', 'asc')->get();
        $ipsLabels = [];
        $ipsData = [];
        foreach ($semesters as $sem) {
            $ipsLabels[] = "Sem " . $sem->urutan;
            if (isset($ipsTren[$sem->id]) && $ipsTren[$sem->id]['sks'] > 0) {
                $ipsData[] = round($ipsTren[$sem->id]['bobot'] / $ipsTren[$sem->id]['sks'], 2);
            } else {
                $ipsData[] = 0;
            }
        }
        
        // --- PRESTASI DISTRIBUTION ---
        $prestasiGroups = ['Internasional' => 0, 'Nasional' => 0, 'Lokal' => 0];
        $prestasiList = \App\Models\PrestasiMahasiswa::all();
        foreach ($prestasiList as $p) {
            $tingkat = ucfirst(strtolower($p->tingkat));
            if (isset($prestasiGroups[$tingkat])) {
                $prestasiGroups[$tingkat]++;
            } else if (stripos($tingkat, 'prov') !== false || stripos($tingkat, 'kota') !== false || stripos($tingkat, 'kampus') !== false) {
                 $prestasiGroups['Lokal']++;
            } else {
                 $prestasiGroups['Lokal']++;
            }
        }
        
        // --- TOP ACHIEVERS ---
        $topAchievers = \App\Models\Mahasiswa::withCount('prestasi')->orderBy('prestasi_count', 'desc')->take(5)->get();
        
        // --- RECENT ACTIVITY ---
        $recentP = \App\Models\PrestasiMahasiswa::with('mahasiswa')->latest('created_at')->take(5)->get()->map(function($i) {
            return ['type' => 'Prestasi', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#8b5cf6', 'text' => ($i->mahasiswa->nama ?? 'Mahasiswa') . ' mencatat prestasi ' . $i->tingkat, 'date' => $i->created_at];
        });
        $recentK = \App\Models\PesanMahasiswa::with('mahasiswa')->latest('created_at')->take(5)->get()->map(function($i) {
            return ['type' => 'Konsultasi', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'color' => '#3b82f6', 'text' => ($i->mahasiswa->nama ?? 'Mahasiswa') . ' mengirim pesan', 'date' => $i->created_at];
        });
        $recentN = \App\Models\Nilai::with('krs.mahasiswa')->latest('created_at')->take(5)->get()->map(function($i) {
            return ['type' => 'Nilai', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'color' => '#10b981', 'text' => 'Nilai baru diinput untuk ' . ($i->krs->mahasiswa->nama ?? 'Mahasiswa'), 'date' => $i->created_at];
        });
        $activities = collect([...$recentP, ...$recentK, ...$recentN])->sortByDesc('date')->take(5);
    @endphp

    <div class="mb-6 flex justify-between items-end border-b pb-4">
        <div>
            <h1 class="text-3xl font-bold mb-1" style="color: #1e293b;">E-RICK Academic Command Center</h1>
            <p class="text-muted">Monitoring Akademik Program Studi</p>
        </div>
        <div class="text-right text-sm">
            <div class="mb-1"><span class="text-muted">Periode Aktif:</span> <span class="font-semibold text-primary">{{ $semesterName }}</span></div>
            <div><span class="text-muted">Update Terakhir:</span> <span class="font-semibold">{{ now()->format('d M Y, H:i') }}</span></div>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card hoverable flex items-center gap-4" style="border-left: 4px solid #3b82f6;">
            <div style="background: #eff6ff; padding: 12px; border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#3b82f6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div>
                <div class="text-muted text-xs font-semibold uppercase">Total Mahasiswa</div>
                <div class="text-2xl font-bold" style="color: #1e293b;">{{ $totalMahasiswa }}</div>
            </div>
        </div>
        
        <div class="card hoverable flex items-center gap-4" style="border-left: 4px solid #10b981;">
            <div style="background: #ecfdf5; padding: 12px; border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#10b981"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <div class="text-muted text-xs font-semibold uppercase">Dosen Wali</div>
                <div class="text-2xl font-bold" style="color: #1e293b;">{{ $totalDosen }}</div>
            </div>
        </div>

        <div class="card hoverable flex items-center gap-4" style="border-left: 4px solid #f59e0b;">
            <div style="background: #fffbeb; padding: 12px; border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#f59e0b"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div>
                <div class="text-muted text-xs font-semibold uppercase">Data Nilai</div>
                <div class="text-2xl font-bold" style="color: #1e293b;">{{ $totalNilai }}</div>
            </div>
        </div>

        <div class="card hoverable flex items-center gap-4" style="border-left: 4px solid #8b5cf6;">
            <div style="background: #f5f3ff; padding: 12px; border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
            <div>
                <div class="text-muted text-xs font-semibold uppercase">Prestasi</div>
                <div class="text-2xl font-bold" style="color: #1e293b;">{{ $totalPrestasi }}</div>
            </div>
        </div>

        <div class="card hoverable flex items-center gap-4" style="border-left: 4px solid #ec4899;">
            <div style="background: #fdf2f8; padding: 12px; border-radius: 50%;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#ec4899"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
            </div>
            <div>
                <div class="text-muted text-xs font-semibold uppercase">Konsultasi</div>
                <div class="text-2xl font-bold" style="color: #1e293b;">{{ $totalKonsultasi }}</div>
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div style="margin-bottom: 2rem;">
        
        <!-- CHART 1 -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Tren Rata-rata IPS per Semester</h3>
            <div style="height: 250px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <!-- CHART 2 -->
            <div class="card" style="margin-bottom: 0;">
                <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Distribusi Status Akademik</h3>
                <div style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- CHART 3 -->
            <div class="card" style="margin-bottom: 0;">
                <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Prestasi Mahasiswa</h3>
                <div style="height: 250px;">
                    <canvas id="prestasiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM PANELS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- INSIGHTS -->
        <div class="card">
            <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Academic Insight</h3>
            <div class="flex" style="flex-direction: column; gap: 0.75rem;">
                <div class="flex justify-between items-center p-3 rounded" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                    <div class="flex items-center gap-2">
                        <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#3b82f6;"></span>
                        <span class="text-sm font-medium" style="color:#1e3a8a;">IPK Tinggi (>= 3.50)</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#1e3a8a;">{{ $ipkSangatBaik }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded" style="background: #fffbeb; border: 1px solid #fde68a;">
                    <div class="flex items-center gap-2">
                        <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#f59e0b;"></span>
                        <span class="text-sm font-medium" style="color:#92400e;">Perlu Perhatian (< 3.00)</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#92400e;">{{ $ipkPerhatian }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded" style="background: #fdf2f8; border: 1px solid #fbcfe8;">
                    <div class="flex items-center gap-2">
                        <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#ec4899;"></span>
                        <span class="text-sm font-medium" style="color:#9d174d;">Tanpa Konsultasi</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#9d174d;">{{ $mahasiswaTanpaKonsultasi }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 rounded" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                    <div class="flex items-center gap-2">
                        <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#10b981;"></span>
                        <span class="text-sm font-medium" style="color:#065f46;">Memiliki Prestasi</span>
                    </div>
                    <span class="font-bold text-lg" style="color:#065f46;">{{ $mahasiswaBerprestasi }}</span>
                </div>
            </div>
        </div>

        <!-- TOP ACHIEVEMENTS -->
        <div class="card">
            <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Top Achievement</h3>
            <div class="flex" style="flex-direction: column; gap: 0.75rem;">
                @forelse($topAchievers as $index => $achiever)
                    <div class="flex items-center" style="gap: 1rem;">
                        <div style="width:36px; height:36px; border-radius:50%; background:{{ ['#fbbf24', '#9ca3af', '#b45309', '#3b82f6', '#8b5cf6'][$index] ?? '#ddd' }}; color:white; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:14px;">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-sm text-gray-800">{{ $achiever->nama }}</div>
                            <div class="text-xs text-muted">{{ $achiever->prestasi_count }} Prestasi</div>
                        </div>
                        @if($achiever->prestasi_count > 0)
                            <div class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full" style="background:#f3e8ff; color:#7e22ce;">
                                Aktif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted text-sm py-4">Belum ada data prestasi.</div>
                @endforelse
            </div>
        </div>

        <!-- RECENT ACTIVITY -->
        <div class="card">
            <h3 class="font-semibold mb-4 text-gray-700 border-b pb-2">Recent Activity</h3>
            <div class="flex relative" style="flex-direction: column; gap: 1rem;">
                <div style="position:absolute; left:16px; top:10px; bottom:10px; width:2px; background:#e5e7eb; z-index:0;"></div>
                @forelse($activities as $act)
                    <div class="flex items-start relative z-10" style="gap: 1rem;">
                        <div style="width:32px; height:32px; border-radius:50%; background:white; border:2px solid {{ $act['color'] }}; display:flex; align-items:center; justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ $act['color'] }}">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-800">{{ $act['text'] }}</div>
                            <div class="text-xs text-muted">{{ $act['date']->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted text-sm py-4">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- QUICK ACCESS MENU -->
    <div class="card mb-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h3 class="font-semibold text-gray-700 m-0 border-0 pb-0">Menu Manajemen Akademik</h3>
            <a href="{{ route('admin.backup.database') }}" class="btn btn-primary" style="background: #10b981; border-color: #10b981; color: white; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; padding: 0.5rem 1rem; border-radius: var(--radius-md); font-size: 0.875rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Backup DB
            </a>
        </div>
        <div class="grid-3">
            <a href="{{ route('pengelola.mahasiswa') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Data Mahasiswa</h4>
            </a>
            <a href="{{ route('pengelola.dosen') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Data Dosen</h4>
            </a>
            <a href="{{ route('pengelola.mata-kuliah') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Mata Kuliah</h4>
            </a>
            <a href="{{ route('pengelola.input-nilai') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Input Nilai</h4>
            </a>
            <a href="{{ route('pengelola.rekap.mahasiswa') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Rekap Mahasiswa</h4>
            </a>
            <a href="{{ route('pengelola.rekap.prestasi') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit; background: #f8fafc; box-shadow: none; border: 1px solid #e2e8f0;">
                <h4 class="font-semibold mb-1 text-sm">Rekap Prestasi</h4>
            </a>
        </div>
    </div>

    <!-- SCRIPT FOR CHARTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Tren IPS
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($ipsLabels) !!},
                    datasets: [{
                        label: 'Rata-rata IPS',
                        data: {!! json_encode($ipsData) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false, min: 2.0, max: 4.0 } }
                }
            });

            // Chart 2: Status Akademik
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Sangat Baik (>= 3.5)', 'Baik (3.0-3.49)', 'Perlu Perhatian (< 3.0)'],
                    datasets: [{
                        data: [{{ $ipkSangatBaik }}, {{ $ipkBaik }}, {{ $ipkPerhatian }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } }
                }
            });

            // Chart 3: Prestasi
            const ctxPrestasi = document.getElementById('prestasiChart').getContext('2d');
            new Chart(ctxPrestasi, {
                type: 'bar',
                data: {
                    labels: ['Internasional', 'Nasional', 'Lokal'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [{{ $prestasiGroups['Internasional'] }}, {{ $prestasiGroups['Nasional'] }}, {{ $prestasiGroups['Lokal'] }}],
                        backgroundColor: ['#8b5cf6', '#c084fc', '#e9d5ff'],
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        });
    </script>

@elseif(auth()->user()->role === 'admin')
    <div class="mb-4">
        <h1 class="text-2xl font-semibold mb-2">Selamat datang, Admin</h1>
        <p class="text-muted">Pilih menu manajemen di bawah ini.</p>
    </div>

    <div class="grid-3">
        <a href="{{ route('pengelola.mahasiswa') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Data Mahasiswa</h3>
            <p class="text-muted text-sm">Kelola mahasiswa dan akun login.</p>
        </a>
        <a href="{{ route('pengelola.dosen') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Data Dosen</h3>
            <p class="text-muted text-sm">Kelola dosen wali.</p>
        </a>
        <a href="{{ route('pengelola.mata-kuliah') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Mata Kuliah</h3>
            <p class="text-muted text-sm">Kelola kurikulum.</p>
        </a>
        <a href="{{ route('pengelola.input-nilai') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Input Nilai</h3>
            <p class="text-muted text-sm">Masukkan nilai KHS.</p>
        </a>
        <a href="{{ route('pengelola.rekap.mahasiswa') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Rekap Mahasiswa</h3>
            <p class="text-muted text-sm">Lihat statistik akademik.</p>
        </a>
        <a href="{{ route('pengelola.rekap.prestasi') }}" class="card hoverable text-center" style="text-decoration: none; color: inherit;">
            <h3 class="font-semibold mb-2">Rekap Prestasi</h3>
            <p class="text-muted text-sm">Prestasi non-akademik.</p>
        </a>
    </div>
@else
    <div class="mb-4">
        <h1 class="text-2xl font-semibold mb-2">Portal E-RICK</h1>
        <p class="text-muted">Selamat datang, {{ auth()->user()->name }}.</p>
    </div>
@endif

@endsection


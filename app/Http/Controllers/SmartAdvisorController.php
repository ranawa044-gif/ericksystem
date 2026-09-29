<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicPrediction;
use App\Models\SpkCriteriaWeight;
use App\Services\AcademicDecisionService;
use App\Services\PamasesaIntegrationService;
use Illuminate\Support\Facades\DB;

class SmartAdvisorController extends Controller
{
    protected $advisorService;
    protected PamasesaIntegrationService $pamasesaService;

    public function __construct(
        AcademicDecisionService $advisorService,
        PamasesaIntegrationService $pamasesaService
    ) {
        $this->advisorService = $advisorService;
        $this->pamasesaService = $pamasesaService;
    }

    public function indexDosen()
    {
        $user = auth()->user();
        if ($user->role !== 'dosen') {
            abort(403);
        }

        $predictions = AcademicPrediction::with('mahasiswa')
            ->whereHas('mahasiswa', function($q) use ($user) {
                $q->where('dosen_wali_id', $user->dosen_id);
            })
            ->orderBy('score', 'asc') // Lowest score (Merah) at the top
            ->get();

        $amanCount = $predictions->where('category', 'Hijau')->count();
        $monitoringCount = $predictions->where('category', 'Kuning')->count();
        $pendampinganCount = $predictions->where('category', 'Merah')->count();

        $nims = $predictions->pluck('mahasiswa.nim')->filter()->values()->toArray();
        $paSummaries = $this->pamasesaService->getBatchPaSummary($nims);

        return view('advisor.dosen', compact('predictions', 'amanCount', 'monitoringCount', 'pendampinganCount', 'paSummaries'));
    }

    public function runAnalysisDosen()
    {
        $user = auth()->user();
        if ($user->role !== 'dosen') {
            abort(403);
        }

        $this->advisorService->calculateAll($user->dosen_id);

        return redirect()->route('advisor.dosen')->with('success', 'Analisis SPK berhasil diperbarui.');
    }

    public function indexAdmin()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $predictions = AcademicPrediction::all();
        $amanCount = $predictions->where('category', 'Hijau')->count();
        $monitoringCount = $predictions->where('category', 'Kuning')->count();
        $pendampinganCount = $predictions->where('category', 'Merah')->count();

        $weights = SpkCriteriaWeight::orderBy('code')->get();

        return view('advisor.admin', compact('amanCount', 'monitoringCount', 'pendampinganCount', 'weights'));
    }

    public function updateWeights(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $weightsData = $request->input('weights');
        if (is_array($weightsData)) {
            foreach ($weightsData as $code => $weight) {
                SpkCriteriaWeight::where('code', $code)->update(['weight' => $weight]);
            }
            
            // Recalculate all after weights change
            $this->advisorService->calculateAll();
        }

        return redirect()->route('advisor.admin')->with('success', 'Bobot kriteria berhasil diperbarui dan semua data telah dianalisis ulang.');
    }
}

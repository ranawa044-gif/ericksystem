<?php

namespace App\Http\Controllers;

use App\Models\SpkCriteriaWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalisisPerancanganController extends Controller
{
    public function index()
    {
        // Hanya dapat diakses oleh Admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses terbatas untuk Administrator.');
        }

        $weights = SpkCriteriaWeight::orderBy('code')->get();
        
        $stats = [
            'total_tables' => 12,
            'total_models' => 13,
            'total_actors' => 3,
            'total_use_cases' => 16,
            'integrated_systems' => ['E-RICK (simnilai)', 'PAMASESA (masesa)'],
        ];

        return view('pengelola.analisis-perancangan', compact('weights', 'stats'));
    }
}

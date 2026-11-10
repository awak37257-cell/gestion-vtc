<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statusCounts = Candidature::query()
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $totalCandidatures = (int) $statusCounts->sum();
        $candidaturesRecentes = Candidature::with('chauffeur')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('statusCounts', 'totalCandidatures', 'candidaturesRecentes'));
    }
}

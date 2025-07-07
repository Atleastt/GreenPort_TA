<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardAuditorController extends Controller
{
    public function index(): View
    {
        // Mengambil semua indikator beserta relasi subkriteria dan kriteria
        $indikators = Indikator::with('subkriteria.kriteria')->get();

        // Menyiapkan data untuk chart
        $chartData = $this->prepareChartData($indikators);

        // Mengirim data ke view
        return view('pages.dashboard_auditor', [
            'indikators' => $indikators,
            'chartData' => json_encode($chartData),
        ]);
    }

    private function prepareChartData($indikators)
    {
        $kriteriaCounts = [];

        foreach ($indikators as $indikator) {
            if (isset($indikator->subkriteria->kriteria)) {
                $kriteriaName = $indikator->subkriteria->kriteria->nama_kriteria;
                if (!isset($kriteriaCounts[$kriteriaName])) {
                    $kriteriaCounts[$kriteriaName] = 0;
                }
                $kriteriaCounts[$kriteriaName]++;
            }
        }

        return [
            'labels' => array_keys($kriteriaCounts),
            'data' => array_values($kriteriaCounts),
        ];
    }
}

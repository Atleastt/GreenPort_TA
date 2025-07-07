<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Arahkan dashboard berdasarkan role menggunakan Spatie Permission
        if ($user->hasRole('Auditor')) {
            return redirect()->route('auditor.audits.index');
        }

        if ($user->hasRole('Auditee')) {
            return redirect()->route('dashboard.auditee');
        }

        // Fallback for other roles like admin
        $indikators = Indikator::with('subkriteria.kriteria')->get();
        $chartData = $this->prepareChartData($indikators);

        return view('dashboard', [
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

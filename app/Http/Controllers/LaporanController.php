<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan.
     */
    /**
     * Menampilkan form untuk membuat laporan baru berdasarkan audit.
     */
    public function create(Audit $audit)
    {
        // Anda bisa memuat data lain yang diperlukan dari audit di sini
        return view('pages.create_laporan_auditor', compact('audit'));
    }

    /**
     * Menampilkan daftar laporan.
     */
    public function index()
    {
        $reports = Laporan::orderBy('created_at', 'desc')->get();
        return view('pages.pelaporan', compact('reports'));
    }

    /**
     * Menyimpan laporan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'audit_id' => 'required|exists:audits,id',
            'report_title' => 'required|string|max:255',
            'executive_summary' => 'nullable|string',
            'findings_recommendations' => 'nullable|string',
            'compliance_score' => 'nullable|numeric|min:0|max:100',
        ]);

        Laporan::create([
            'audit_id' => $request->audit_id,
            'title' => $request->report_title,
            'executive_summary' => $request->executive_summary,
            'findings_recommendations' => $request->findings_recommendations,
            'compliance_score' => $request->compliance_score,
        ]);

        return redirect()->route('pelaporan')->with('success', 'Laporan audit berhasil dibuat.');
    }
    //
}

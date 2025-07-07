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
        // Check if audit is completed
        if ($audit->status !== 'Completed') {
            return redirect()->route('daftar.audit.auditor')
                ->with('error', 'Hanya audit yang sudah selesai yang dapat dibuatkan laporan.');
        }

        // Check if report already exists
        $existingReport = Laporan::where('audit_id', $audit->id)->first();
        if ($existingReport) {
            return redirect()->route('daftar.audit.auditor')
                ->with('error', 'Laporan untuk audit ini sudah dibuat.');
        }
        return view('pages.create_laporan_auditor', compact('audit'));
    }

    /**
     * Menampilkan daftar laporan.
     */
    public function index()
    {
        $reports = Laporan::orderBy('created_at', 'desc')->get();
        // dd($reports);
        $audit = Audit::all();
        return view('pages.pelaporan', compact('reports', 'audit'));
    }

    /**
     * Menyimpan laporan baru ke database.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'audit_id' => 'required|exists:audits,id',
            'report_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'report_title' => 'nullable|string|max:255',
            'executive_summary' => 'nullable|string',
            'findings_recommendations' => 'nullable|string',
            'compliance_score' => 'nullable|numeric|min:0|max:100',
        ]);
        // dd($validate);
        // Check if report already exists for this audit
        $existingReport = Laporan::where('audit_id', $validate["audit_id"])->first();
        // dd($existingReport);
        if ($existingReport) {
            return redirect()->back()->withErrors(['audit_id' => 'Laporan untuk audit ini sudah dibuat.']);
        }
        // dd((int) $validate['audit_id']);
        // Verify that the audit is completed
        $audit = Audit::findOrFail((int) $validate["audit_id"]);
        if ($audit->status !== 'Completed') {
            return redirect()->back()->withErrors(['audit_id' => 'Hanya audit yang sudah selesai yang dapat dibuatkan laporan.']);
        }

        $laporan = Laporan::create([
            'audit_id' => (int) $validate["audit_id"],
            'title' => '',
            'executive_summary' => '',
            'findings_recommendations' => '',
            'compliance_score' => 0,
            'period_start' => $validate["start_date"],
            'period_end' => $validate["end_date"],
            // 'period_start' => $audit->scheduled_start_date,
            // 'period_end' => $audit->scheduled_end_date,
        ]);


        return redirect()->route('daftar.audit.auditor')->with('success', 'Laporan audit berhasil dibuat.');
    }
    //
}

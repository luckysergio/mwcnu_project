<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Jadwal_proker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AnggaranController extends Controller
{

    public function downloadPdf(Request $request)
    {
        $jadwalProkerId = $request->get('jadwal_proker_id');

        $anggarans = Anggaran::where('jadwal_proker_id', $jadwalProkerId)
            ->with('jadwalProker.proker')
            ->get();

        $total = $anggarans->sum('jumlah');

        $prokerName = 'Semua Program Kerja';
        if ($jadwalProkerId && $anggarans->isNotEmpty()) {
            $prokerName = $anggarans->first()->jadwalProker->proker->program ?? 'Tanpa Nama Proker';
        } elseif ($jadwalProkerId) {
            $jadwalProker = Jadwal_proker::with('proker')->find($jadwalProkerId);
            $prokerName = $jadwalProker?->proker?->program ?? 'Tanpa Nama Proker';
        }

        $safeProkerName = strtolower(str_replace([' ', '/', '\\'], '-', $prokerName));
        $filename = 'laporan-anggaran-' . $safeProkerName . '.pdf';

        $pdf = PDF::loadView('pages.anggaran.pdf', compact('anggarans', 'total', 'prokerName'));

        return $pdf->download($filename);
    }

    public function index(Request $request)
    {
        $jadwalId = $request->input('jadwal_proker_id');

        // Ambil semua jadwal proker yang berstatus penjadwalan
        $jadwalProkers = Jadwal_proker::with('proker')
            ->where('status', 'penjadwalan')
            ->get();

        $query = Anggaran::with('jadwalProker.proker');

        if ($jadwalId) {
            $query->where('jadwal_proker_id', $jadwalId);
        }

        $anggarans = $query->latest()->get();

        return view('pages.anggaran.index', compact('anggarans', 'jadwalProkers', 'jadwalId'));
    }

    public function create()
    {
        $jadwalProkers = Jadwal_proker::with('proker')
            ->where('status', 'penjadwalan')
            ->get();

        return view('pages.anggaran.create', compact('jadwalProkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_proker_id' => 'required|exists:jadwal_prokers,id',
            'pendana' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:100',
        ]);

        Anggaran::create([
            'jadwal_proker_id' => $request->jadwal_proker_id,
            'pendana' => $request->pendana,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $anggaran = Anggaran::findOrFail($id);
        $jadwalProkers = Jadwal_proker::with('proker')
            ->where('status', 'penjadwalan')
            ->get();

        return view('pages.anggaran.edit', compact('anggaran', 'jadwalProkers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jadwal_proker_id' => 'required|exists:jadwal_prokers,id',
            'pendana' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:100',
        ]);

        $anggaran = Anggaran::findOrFail($id);
        $anggaran->update([
            'jadwal_proker_id' => $request->jadwal_proker_id,
            'pendana' => $request->pendana,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggaran = Anggaran::findOrFail($id);
        $anggaran->delete();

        return redirect()->route('anggaran.index')->with('success', 'Data anggaran berhasil dihapus.');
    }
}

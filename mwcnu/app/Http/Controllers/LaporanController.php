<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Proker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('proker.jadwalProker.details')->latest()->paginate(10);

        $unreportedCount = Proker::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'selesai');
        })
        ->whereDoesntHave('laporan')
        ->count();

        return view('pages.laporan.index', compact('laporans', 'unreportedCount'));
    }

    public function create()
    {
        $prokers = Proker::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'selesai');
        })
        ->whereDoesntHave('laporan')
        ->orderBy('judul')
        ->get();

        return view('pages.laporan.create', compact('prokers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proker_id' => 'required|exists:prokers,id',
            'catatan'   => 'nullable|string',
            'foto.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPaths = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('laporan', 'public');
                $fotoPaths[] = basename($path);
            }
        }

        Laporan::create([
            'proker_id' => $request->proker_id,
            'catatan'   => $request->catatan,
            'foto'      => $fotoPaths,
        ]);

        return back()->with('success', 'Laporan berhasil disimpan.');
    }

    public function edit($id)
    {
        $laporan = Laporan::with('proker')->findOrFail($id);

        $prokers = Proker::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'selesai');
        })
        ->orderBy('judul')
        ->get();

        return view('pages.laporan.edit', compact('laporan', 'prokers'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'proker_id' => 'required|exists:prokers,id',
            'catatan'   => 'nullable|string',
            'foto.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Pastikan selalu array
        $fotoPaths = is_array($laporan->foto) ? $laporan->foto : [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('laporan', 'public');
                $fotoPaths[] = basename($path);
            }
        }

        $laporan->update([
            'proker_id' => $request->proker_id,
            'catatan'   => $request->catatan,
            'foto'      => $fotoPaths,
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function exportPdf($id)
    {
        $laporan = Laporan::with('proker.jadwalProker.details')->findOrFail($id);

        $pdf = Pdf::loadView('pages.laporan.pdf', compact('laporan'))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-' . $laporan->proker->judul . '.pdf');
    }
}

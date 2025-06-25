<?php

namespace App\Http\Controllers;

use App\Models\JadwalProker;
use App\Models\JadwalProkerDetail;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalProkerController extends Controller
{
    public function countUnassignedProker()
    {
        $count = Proker::where('status', 'disetujui')
            ->doesntHave('jadwalProker')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        $jadwals = JadwalProker::with(['proker', 'penanggungJawab', 'details'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->get();

        $unassignedCount = Proker::where('status', 'disetujui')
            ->doesntHave('jadwalProker')
            ->count();

        return view('pages.jadwal_proker.index', compact('jadwals', 'unassignedCount'));
    }

    public function create()
    {
        $prokers = Proker::where('status', 'disetujui')
            ->doesntHave('jadwalProker')
            ->get();

        return view('pages.jadwal_proker.create', compact('prokers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proker_id' => 'required|exists:prokers,id',
            'status' => 'required|in:penjadwalan,berjalan,selesai',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'tanggal_mulai' => 'required|array',
            'tanggal_mulai.*' => 'required|date',
            'tanggal_selesai' => 'required|array',
            'tanggal_selesai.*' => 'required|date|after_or_equal:tanggal_mulai.*',
            'catatan' => 'nullable|array'
        ]);

        DB::transaction(function () use ($request) {
            $proker = Proker::findOrFail($request->proker_id);
            $jadwal = JadwalProker::create([
                'proker_id' => $request->proker_id,
                'penanggung_jawab_id' => $proker->anggota_id,
                'status' => $request->status,
            ]);

            foreach ($request->kegiatan as $i => $keg) {
                JadwalProkerDetail::create([
                    'jadwal_proker_id' => $jadwal->id,
                    'kegiatan' => $keg,
                    'tanggal_mulai' => $request->tanggal_mulai[$i],
                    'tanggal_selesai' => $request->tanggal_selesai[$i],
                    'catatan' => $request->catatan[$i] ?? null,
                ]);
            }
        });

        return back()->with('success', 'Jadwal proker berhasil dibuat.');
    }

    public function edit($id)
    {
        $jadwal = JadwalProker::with(['details', 'proker'])->findOrFail($id);
        $prokers = Proker::whereDoesntHave('jadwalProker')
            ->orWhere('id', $jadwal->proker_id)
            ->get();

        return view('pages.jadwal_proker.edit', compact('jadwal', 'prokers'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'proker_id' => 'required|exists:prokers,id',
        'status' => 'required|in:penjadwalan,berjalan,selesai',

        'kegiatan' => 'required|array|min:1',
        'kegiatan.*' => 'required|string|max:255',

        'tanggal_mulai' => 'required|array|min:1',
        'tanggal_mulai.*' => 'required|date',

        'tanggal_selesai' => 'required|array|min:1',
        'tanggal_selesai.*' => 'required|date',

        'catatan' => 'nullable|array',
        'catatan.*' => 'nullable|string',
    ]);

    DB::transaction(function () use ($request, $id) {
        $jadwal = JadwalProker::findOrFail($id);
        $proker = Proker::findOrFail($request->proker_id);

        // Update header
        $jadwal->update([
            'proker_id' => $request->proker_id,
            'penanggung_jawab_id' => $proker->anggota_id,
            'status' => $request->status,
        ]);

        // Hapus semua detail lama
        $jadwal->details()->delete();

        // Tambah detail baru
        foreach ($request->kegiatan as $i => $keg) {
            JadwalProkerDetail::create([
                'jadwal_proker_id' => $jadwal->id,
                'kegiatan' => $keg,
                'tanggal_mulai' => $request->tanggal_mulai[$i],
                'tanggal_selesai' => $request->tanggal_selesai[$i],
                'catatan' => $request->catatan[$i] ?? null,
            ]);
        }
    });

    return redirect()->route('jadwal-proker.index')->with('success', 'Jadwal berhasil diperbarui.');
}


    public function destroy($id)
    {
        $jadwal = JadwalProker::findOrFail($id);
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function semuaJadwal()
    {
        $penjadwalan = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'penjadwalan');
        })->with('jadwalProker.proker')->get();

        $berjalan = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'berjalan');
        })->with('jadwalProker.proker')->get();

        $selesai = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
            $q->where('status', 'selesai');
        })->with('jadwalProker.proker')->get();

        return view('pages.dashboard', compact('penjadwalan', 'berjalan', 'selesai'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JadwalProker;
use App\Models\JadwalProkerDetail;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class JadwalProkerController extends Controller
{
    public function countUnassignedProker()
    {
        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        $count = Proker::where('status', 'disetujui')
            ->when($anggotaStatus !== 'MWC', function ($q) use ($rantingId) {
                $q->where('ranting_id', $rantingId);
            })
            ->doesntHave('jadwalProker')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->anggota) {
            return back()->withErrors('Akun ini belum terhubung dengan data anggota.');
        }

        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId     = $user->anggota->ranting_id;

        $jadwalsQuery = JadwalProker::with(['proker', 'penanggungJawab', 'details']);

        if ($request->filled('status')) {
            $jadwalsQuery->where('status', $request->status);
        }

        // Jika bukan MWC, filter berdasarkan ranting
        if ($anggotaStatus !== 'MWC') {
            $jadwalsQuery->whereHas('proker', function ($q) use ($rantingId) {
                $q->where('ranting_id', $rantingId);
            });
        }

        $jadwals = $jadwalsQuery->get();

        $unassignedCountQuery = Proker::where('status', 'disetujui');

        if ($anggotaStatus !== 'MWC') {
            $unassignedCountQuery->where('ranting_id', $rantingId);
        }

        $unassignedCount = $unassignedCountQuery->doesntHave('jadwalProker')->count();

        return view('pages.jadwal_proker.index', compact('jadwals', 'unassignedCount'));
    }

    public function create()
    {
        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        // Filter proker sesuai ranting jika bukan MWC
        $prokers = Proker::where('status', 'disetujui')
            ->doesntHave('jadwalProker')
            ->when($anggotaStatus !== 'MWC', function ($q) use ($rantingId) {
                $q->where('ranting_id', $rantingId);
            })
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

        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        $proker = Proker::findOrFail($request->proker_id);

        // Pastikan user ranting tidak bisa membuat jadwal untuk ranting lain
        if ($anggotaStatus !== 'MWC' && $proker->ranting_id != $rantingId) {
            abort(403, "Anda tidak dapat membuat jadwal untuk proker ranting lain.");
        }

        DB::transaction(function () use ($request, $proker) {
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
        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        $jadwal = JadwalProker::with(['details', 'proker'])->findOrFail($id);

        // Batasi akses edit hanya untuk ranting sama
        if ($anggotaStatus !== 'MWC' && $jadwal->proker->ranting_id != $rantingId) {
            abort(403, "Anda tidak memiliki akses untuk mengedit jadwal proker ini.");
        }

        $prokers = Proker::whereDoesntHave('jadwalProker')
            ->orWhere('id', $jadwal->proker_id)
            ->when($anggotaStatus !== 'MWC', function ($q) use ($rantingId) {
                $q->where('ranting_id', $rantingId);
            })
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

        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        $proker = Proker::findOrFail($request->proker_id);

        // Validasi akses update
        if ($anggotaStatus !== 'MWC' && $proker->ranting_id != $rantingId) {
            abort(403, "Anda tidak dapat mengubah jadwal untuk proker milik ranting lain.");
        }

        DB::transaction(function () use ($request, $id, $proker) {
            $jadwal = JadwalProker::findOrFail($id);

            $jadwal->update([
                'proker_id' => $request->proker_id,
                'penanggung_jawab_id' => $proker->anggota_id,
                'status' => $request->status,
            ]);

            $jadwal->details()->delete();

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
        $user = Auth::user();
        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        $jadwal = JadwalProker::with('proker')->findOrFail($id);

        // Batasi delete berdasarkan ranting
        if ($anggotaStatus !== 'MWC' && $jadwal->proker->ranting_id != $rantingId) {
            abort(403, "Anda tidak dapat menghapus jadwal ini.");
        }

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

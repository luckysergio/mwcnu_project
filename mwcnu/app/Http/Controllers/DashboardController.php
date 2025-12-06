<?php

namespace App\Http\Controllers;

use App\Models\JadwalProker;
use App\Models\Ranting;
use App\Models\Proker;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwals = JadwalProker::with('proker.ranting')
            ->whereHas('proker', function ($q) {
                $q->where('status', 'disetujui');
            })
            ->whereNotNull('estimasi_mulai')
            ->whereNotNull('estimasi_selesai')
            ->get();

        $events = $jadwals->map(function ($jadwal) {
            $rantingNama = $jadwal->proker->ranting?->kelurahan ?? 'Ranting tidak diketahui';
            return [
                'title'  => "{$jadwal->proker->judul} - {$rantingNama}",
                'start'  => $jadwal->estimasi_mulai,
                'end'    => $jadwal->estimasi_selesai,
                'status' => $jadwal->status,
            ];
        })->values();

        $rantings = Ranting::all();

        $data = $rantings->map(function ($ranting) {
            $prokers = Proker::where('ranting_id', $ranting->id)
                ->where('status', 'disetujui')
                ->with('jadwalProker')
                ->get();

            $penjadwalan = $prokers->filter(fn($p) => $p->jadwalProker?->status === 'penjadwalan')->count();
            $berjalan    = $prokers->filter(fn($p) => $p->jadwalProker?->status === 'berjalan')->count();
            $selesai     = $prokers->filter(fn($p) => $p->jadwalProker?->status === 'selesai')->count();

            return [
                'nama'         => $ranting->kelurahan,
                'total_proker' => $prokers->count(),
                'penjadwalan'  => $penjadwalan,
                'berjalan'     => $berjalan,
                'selesai'      => $selesai,
            ];
        });

        $labels      = $data->pluck('nama');
        $penjadwalan = $data->pluck('penjadwalan');
        $berjalan    = $data->pluck('berjalan');
        $selesai     = $data->pluck('selesai');

        return view('pages.dashboard.index', compact(
            'events',
            'data',
            'labels',
            'penjadwalan',
            'berjalan',
            'selesai'
        ));
    }
}

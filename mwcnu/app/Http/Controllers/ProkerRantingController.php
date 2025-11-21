<?php

namespace App\Http\Controllers;

use App\Models\JadwalProkerDetail;
use App\Models\Ranting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProkerRantingController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->anggota) {
            return back()->withErrors('Akun ini belum terhubung dengan data anggota.');
        }

        // Status anggota: MWC atau Ranting
        $anggotaStatus = $user->anggota->status->status ?? null;

        // Ranting user
        $rantingId = $user->anggota->ranting_id;

        
        if ($anggotaStatus === 'MWC') {

            $listRanting = Ranting::all();

            $selectedRanting = $request->get('ranting_id');

            if ($selectedRanting) {

                $penjadwalan = $this->getProkerByStatus($selectedRanting, 'penjadwalan');
                $berjalan    = $this->getProkerByStatus($selectedRanting, 'berjalan');
                $selesai     = $this->getProkerByStatus($selectedRanting, 'selesai');

            } else {

                // Jika belum memilih ranting → tampilkan semua program kerja
                $penjadwalan = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
                    $q->where('status', 'penjadwalan');
                })->get();

                $berjalan = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
                    $q->where('status', 'berjalan');
                })->get();

                $selesai = JadwalProkerDetail::whereHas('jadwalProker', function ($q) {
                    $q->where('status', 'selesai');
                })->get();
            }

            return view('pages.proker-ranting.index', compact(
                'penjadwalan',
                'berjalan',
                'selesai',
                'listRanting',
                'selectedRanting'
            ));
        }

        // =====================================================
        // JIKA USER RANTING BIASA
        // =====================================================
        $penjadwalan = $this->getProkerByStatus($rantingId, 'penjadwalan');
        $berjalan    = $this->getProkerByStatus($rantingId, 'berjalan');
        $selesai     = $this->getProkerByStatus($rantingId, 'selesai');

        return view('pages.proker-ranting.index', compact(
            'penjadwalan',
            'berjalan',
            'selesai'
        ));
    }


    /**
     * Ambil program kerja berdasarkan ranting dan status
     */
    private function getProkerByStatus($rantingId, $status)
    {
        return JadwalProkerDetail::whereHas('jadwalProker', function ($q) use ($rantingId, $status) {

            // Status ada di tabel jadwal_prokers
            $q->where('status', $status)

                // Filter proker yang sesuai ranting
                ->whereHas('proker', function ($sub) use ($rantingId) {
                    $sub->where('ranting_id', $rantingId);
                });

        })->get();
    }
}

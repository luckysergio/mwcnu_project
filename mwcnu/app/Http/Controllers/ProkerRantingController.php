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

        $anggotaStatus = $user->anggota->status->status ?? null;
        $rantingId = $user->anggota->ranting_id;

        if ($anggotaStatus === 'MWC') {

            $listRanting = Ranting::all();
            $selectedRanting = $request->get('ranting_id');

            if ($selectedRanting) {

                $penjadwalan = $this->getProkerByStatus($selectedRanting, 'penjadwalan');
                $berjalan    = $this->getProkerByStatus($selectedRanting, 'berjalan');
                $selesai     = $this->getProkerSelesaiGroup($selectedRanting);

            } else {

                $penjadwalan = JadwalProkerDetail::with('jadwalProker.proker.ranting')
                    ->whereHas('jadwalProker', fn ($q) => $q->where('status', 'penjadwalan'))
                    ->get();

                $berjalan = JadwalProkerDetail::with('jadwalProker.proker.ranting')
                    ->whereHas('jadwalProker', fn ($q) => $q->where('status', 'berjalan'))
                    ->get();

                $selesai = JadwalProkerDetail::with('jadwalProker.proker.ranting')
                    ->whereHas('jadwalProker', fn ($q) => $q->where('status', 'selesai'))
                    ->get()
                    ->groupBy(fn ($item) => $item->jadwalProker->proker->id);
            }

            return view('pages.proker-ranting.index', compact(
                'penjadwalan',
                'berjalan',
                'selesai',
                'listRanting',
                'selectedRanting'
            ));
        }

        // Selain MWC (Ranting biasa)
        $penjadwalan = $this->getProkerByStatus($rantingId, 'penjadwalan');
        $berjalan    = $this->getProkerByStatus($rantingId, 'berjalan');
        $selesai     = $this->getProkerSelesaiGroup($rantingId);

        return view('pages.proker-ranting.index', compact(
            'penjadwalan',
            'berjalan',
            'selesai'
        ));
    }

    // Untuk PENJADWALAN & BERJALAN → Masih per kegiatan (detail)
    private function getProkerByStatus($rantingId, $status)
    {
        return JadwalProkerDetail::with('jadwalProker.proker.ranting')
            ->whereHas('jadwalProker', function ($q) use ($rantingId, $status) {

                $q->where('status', $status)
                    ->whereHas('proker', fn ($sub) => $sub->where('ranting_id', $rantingId));
            })->get();
    }

    // ✅ KHUSUS SELESAI — DIGROUP PER PROKER
    private function getProkerSelesaiGroup($rantingId)
    {
        return JadwalProkerDetail::with('jadwalProker.proker.ranting')
            ->whereHas('jadwalProker', function ($q) use ($rantingId) {

                $q->where('status', 'selesai')
                    ->whereHas('proker', fn ($sub) => $sub->where('ranting_id', $rantingId));
            })
            ->get()
            ->groupBy(fn ($item) => $item->jadwalProker->proker->id);
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $prokerDetail = JadwalProkerDetail::findOrFail($id);

        $uploadedFiles = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('proker', 'public');
                $uploadedFiles[] = $path;
            }

            $existingFotos = $prokerDetail->foto ? json_decode($prokerDetail->foto, true) : [];
            $prokerDetail->foto = json_encode(array_merge($existingFotos, $uploadedFiles));
            $prokerDetail->save();
        }

        return back()->with('success', 'Foto berhasil diupload.');
    }
}

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
                $selesai     = $this->getProkerByStatus($selectedRanting, 'selesai');
            } else {

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

        
        $penjadwalan = $this->getProkerByStatus($rantingId, 'penjadwalan');
        $berjalan    = $this->getProkerByStatus($rantingId, 'berjalan');
        $selesai     = $this->getProkerByStatus($rantingId, 'selesai');

        return view('pages.proker-ranting.index', compact(
            'penjadwalan',
            'berjalan',
            'selesai'
        ));
    }
    private function getProkerByStatus($rantingId, $status)
    {
        return JadwalProkerDetail::whereHas('jadwalProker', function ($q) use ($rantingId, $status) {

            $q->where('status', $status)

                ->whereHas('proker', function ($sub) use ($rantingId) {
                    $sub->where('ranting_id', $rantingId);
                });
        })->get();
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB per foto
        ]);

        $prokerDetail = JadwalProkerDetail::findOrFail($id);

        $uploadedFiles = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('proker', 'public'); // simpan di storage/app/public/proker
                $uploadedFiles[] = $path;
            }

            $existingFotos = $prokerDetail->foto ? json_decode($prokerDetail->foto, true) : [];
            $prokerDetail->foto = json_encode(array_merge($existingFotos, $uploadedFiles));
            $prokerDetail->save();
        }

        return back()->with('success', 'Foto berhasil diupload.');
    }
}

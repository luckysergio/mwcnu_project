<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\JenisKegiatan;
use App\Models\Proker;
use App\Models\Sasaran;
use App\Models\Tujuan;
use App\Models\JadwalProker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProkerController extends Controller
{
    public function proker_request()
    {
        $prokers = Proker::with([
            'anggota.user',
            'anggota.status',
            'bidang',
            'jenis',
            'tujuan',
            'sasaran',
            'jadwalProker',
            'ranting'
        ])
            ->where('status', 'pengajuan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.programkerja.request', compact('prokers'));
    }

    public function proker_approval(Request $request, $prokerId)
    {
        $request->validate([
            'for' => 'required|in:approve,reject',
        ]);

        $proker = Proker::with('anggota.status', 'jadwalProker')->findOrFail($prokerId);

        $statusPembuat = $proker->anggota->status->status ?? null;

        if ($statusPembuat === 'MWC') {

            if ($request->for === 'approve') {

                $proker->status = 'disetujui';
                $proker->save();

                $message = 'Proker MWC berhasil disetujui.';
            } else { // === REJECT MWC ===

                DB::beginTransaction();

                try {
                    // Update proker
                    $proker->update([
                        'status'     => 'disetujui', // atau 'perlu direvisi' jika mau
                        'ranting_id' => null,
                    ]);

                    // HAPUS jadwalnya, bukan hanya dikosongkan
                    if ($proker->jadwalProker) {
                        $proker->jadwalProker()->delete();
                    }

                    DB::commit();

                    $message = 'Proker MWC ditolak oleh Ranting dan jadwal dihapus.';
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors('Gagal memproses reject MWC: ' . $e->getMessage());
                }
            }
        } elseif ($statusPembuat === 'Ranting') {

            if ($request->for === 'approve') {
                $proker->status = 'disetujui';
                $message = 'Proker Ranting berhasil disetujui.';
            } else {
                $proker->status = 'ditolak';

                // Optional: kalau mau saat reject ranting juga hapus jadwal
                if ($proker->jadwalProker) {
                    $proker->jadwalProker()->delete();
                }

                $message = 'Proker Ranting ditolak dan jadwal dihapus.';
            }

            $proker->save();
        } else {
            return back()->withErrors('Status pembuat proker tidak valid.');
        }

        return back()->with('success', $message);
    }


    public function countSubmittedProker()
    {
        $count = Proker::where('status', 'pengajuan')->count();
        return response()->json(['count' => $count]);
    }

    public function countBelumJadwal()
    {
        $count = Proker::where('status', 'di setujui')
            ->doesntHave('jadwal')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function index(Request $request)
    {
        $query = Proker::with([
            'anggota.user',
            'anggota.status',
            'bidang',
            'jenis',
            'tujuan',
            'sasaran',
            'ranting',
            'jadwalProker'
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prokers = $query->latest()->paginate(9)->withQueryString();

        return view('pages.programkerja.index', compact('prokers'));
    }

    public function create()
    {
        return view('pages.programkerja.create', [
            'bidangs' => Bidang::orderBy('nama')->get(),
            'jenisKegiatans' => JenisKegiatan::orderBy('nama')->get(),
            'tujuans' => Tujuan::orderBy('nama')->get(),
            'sasarans' => Sasaran::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'             => 'required|string|max:100',
            'proposal'          => 'required|mimes:pdf|max:2048',
            'keterangan'        => 'nullable|string',
            'estimasi_mulai'    => 'required|date',
            'estimasi_selesai'  => 'required|date|after_or_equal:estimasi_mulai'
        ]);

        if (!Auth::user()->anggota) {
            return back()->withErrors('Akun ini belum terhubung dengan data anggota.');
        }

        $anggota     = Auth::user()->anggota;
        $ranting_id  = $anggota->ranting_id ?? null;

        // ============ VALIDASI RANGE TANGGAL (ANTI OVERLAP) ============
        $exists = JadwalProker::where(function ($query) use ($request) {
            $query->whereBetween('estimasi_mulai', [$request->estimasi_mulai, $request->estimasi_selesai])
                ->orWhereBetween('estimasi_selesai', [$request->estimasi_mulai, $request->estimasi_selesai])
                ->orWhere(function ($q) use ($request) {
                    $q->where('estimasi_mulai', '<=', $request->estimasi_mulai)
                        ->where('estimasi_selesai', '>=', $request->estimasi_selesai);
                });
        })->exists();

        if ($exists) {
            return back()->withErrors('Tanggal yang dipilih bertabrakan dengan jadwal proker lain.');
        }

        // ============ BIDANG ============
        if ($request->bidang_id === 'add_new') {
            $request->validate(['new_bidang' => 'required|string|max:50']);
            $bidang_id = Bidang::create(['nama' => $request->new_bidang])->id;
        } else {
            $request->validate(['bidang_id' => 'required|exists:bidangs,id']);
            $bidang_id = $request->bidang_id;
        }

        // ============ JENIS ============
        if ($request->jenis_id === 'add_new') {
            $request->validate(['new_jenis' => 'required|string|max:50']);
            $jenis_id = JenisKegiatan::create(['nama' => $request->new_jenis])->id;
        } else {
            $request->validate(['jenis_id' => 'required|exists:jenis_kegiatans,id']);
            $jenis_id = $request->jenis_id;
        }

        // ============ TUJUAN ============
        if ($request->tujuan_id === 'add_new') {
            $request->validate(['new_tujuan' => 'required|string|max:50']);
            $tujuan_id = Tujuan::create(['nama' => $request->new_tujuan])->id;
        } else {
            $request->validate(['tujuan_id' => 'required|exists:tujuans,id']);
            $tujuan_id = $request->tujuan_id;
        }

        // ============ SASARAN ============
        if ($request->sasaran_id === 'add_new') {
            $request->validate(['new_sasaran' => 'required|string|max:50']);
            $sasaran_id = Sasaran::create(['nama' => $request->new_sasaran])->id;
        } else {
            $request->validate(['sasaran_id' => 'required|exists:sasarans,id']);
            $sasaran_id = $request->sasaran_id;
        }


        DB::beginTransaction();

        try {
            // Upload proposal
            $filePath = $request->file('proposal')->store('proposals', 'public');

            // Simpan Proker
            $proker = Proker::create([
                'anggota_id' => $anggota->id,
                'ranting_id' => $ranting_id,
                'judul'      => $request->judul,
                'bidang_id'  => $bidang_id,
                'jenis_id'   => $jenis_id,
                'tujuan_id'  => $tujuan_id,
                'sasaran_id' => $sasaran_id,
                'proposal'   => $filePath,
                'keterangan' => $request->keterangan,
            ]);

            // Simpan Jadwal Proker
            JadwalProker::create([
                'proker_id'            => $proker->id,
                'penanggung_jawab_id'  => $anggota->id,
                'estimasi_mulai'        => $request->estimasi_mulai,
                'estimasi_selesai'      => $request->estimasi_selesai,
                'status'                => 'penjadwalan'
            ]);

            DB::commit();

            return redirect()
                ->route('proker.index')
                ->with('success', 'Program kerja berhasil diajukan dan dijadwalkan.');
        } catch (\Exception $e) {

            DB::rollBack();

            // Hapus file jika gagal
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->withErrors(
                'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            );
        }
    }

    public function edit(Proker $proker)
    {
        return view('pages.programkerja.edit', [
            'proker' => $proker,
            'bidangs' => Bidang::all(),
            'jenisKegiatans' => JenisKegiatan::all(),
            'tujuans' => Tujuan::all(),
            'sasarans' => Sasaran::all(),
        ]);
    }

    public function update(Request $request, Proker $proker)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'bidang_id' => 'required',
            'jenis_id' => 'required',
            'tujuan_id' => 'required',
            'sasaran_id' => 'required',
            'proposal' => 'nullable|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:pengajuan,disetujui,ditolak',
        ]);


        if ($request->bidang_id === 'add_new') {
            $request->validate(['new_bidang' => 'required|string']);
            $request->merge([
                'bidang_id' => Bidang::create(['nama' => $request->new_bidang])->id
            ]);
        }

        if ($request->jenis_id === 'add_new') {
            $request->validate(['new_jenis' => 'required|string']);
            $request->merge([
                'jenis_id' => JenisKegiatan::create(['nama' => $request->new_jenis])->id
            ]);
        }

        if ($request->tujuan_id === 'add_new') {
            $request->validate(['new_tujuan' => 'required|string']);
            $request->merge([
                'tujuan_id' => Tujuan::create(['nama' => $request->new_tujuan])->id
            ]);
        }

        if ($request->sasaran_id === 'add_new') {
            $request->validate(['new_sasaran' => 'required|string']);
            $request->merge([
                'sasaran_id' => Sasaran::create(['nama' => $request->new_sasaran])->id
            ]);
        }


        if ($request->hasFile('proposal')) {

            if ($proker->proposal && Storage::disk('public')->exists($proker->proposal)) {
                Storage::disk('public')->delete($proker->proposal);
            }

            $path = $request->file('proposal')->store('proposals', 'public');
            $proker->proposal = $path;
        }


        $proker->update([
            'judul'        => $request->judul,
            'bidang_id'    => $request->bidang_id,
            'jenis_id'     => $request->jenis_id,
            'tujuan_id'    => $request->tujuan_id,
            'sasaran_id'   => $request->sasaran_id,
            'keterangan'   => $request->keterangan,
            'status'       => $request->status,
            'proposal'     => $proker->proposal,
        ]);

        return redirect()
            ->route('proker.index')
            ->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy(Proker $proker)
    {
        if ($proker->proposal && Storage::exists($proker->proposal)) {
            Storage::delete($proker->proposal);
        }

        $proker->delete();
        return back()->with('success', 'Data program kerja berhasil dihapus.');
    }
}

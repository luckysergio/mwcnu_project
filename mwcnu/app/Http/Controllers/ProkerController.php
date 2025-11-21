<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\JenisKegiatan;
use App\Models\Proker;
use App\Models\Sasaran;
use App\Models\Tujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProkerController extends Controller
{
    public function proker_request()
    {
        $prokers = Proker::with(['anggota.user', 'bidang', 'jenis', 'tujuan', 'sasaran'])
            ->where('status', 'pengajuan')
            ->latest()
            ->get();

        return view('pages.programkerja.request', compact('prokers'));
    }

    public function proker_approval(Request $request, $prokerId)
    {
        $request->validate([
            'for' => 'required|in:approve,reject',
        ]);

        $proker = Proker::findOrFail($prokerId);

        $proker->status = $request->for === 'approve' ? 'disetujui' : 'ditolak';
        $proker->save();

        $message = $request->for === 'approve'
            ? 'Program kerja berhasil disetujui.'
            : 'Program kerja berhasil ditolak.';

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
        $query = Proker::with(['anggota.user', 'bidang', 'jenis', 'tujuan', 'sasaran']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $prokers = $query->latest()->get();

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
            'judul' => 'required|string|max:100',
            'proposal' => 'required|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        if (!Auth::user()->anggota) {
            return back()->withErrors('Akun ini belum terhubung dengan data anggota.');
        }


        if ($request->bidang_id === 'add_new') {
            $request->validate([
                'new_bidang' => 'required|string|max:50'
            ]);

            $bidang = Bidang::create(['nama' => $request->new_bidang]);
            $bidang_id = $bidang->id;
        } else {
            $request->validate([
                'bidang_id' => 'required|exists:bidangs,id'
            ]);
            $bidang_id = $request->bidang_id;
        }


        if ($request->jenis_id === 'add_new') {
            $request->validate([
                'new_jenis' => 'required|string|max:50'
            ]);

            $jenis = JenisKegiatan::create(['nama' => $request->new_jenis]);
            $jenis_id = $jenis->id;
        } else {
            $request->validate([
                'jenis_id' => 'required|exists:jenis_kegiatans,id'
            ]);
            $jenis_id = $request->jenis_id;
        }


        if ($request->tujuan_id === 'add_new') {
            $request->validate([
                'new_tujuan' => 'required|string|max:50'
            ]);

            $tujuan = Tujuan::create(['nama' => $request->new_tujuan]);
            $tujuan_id = $tujuan->id;
        } else {
            $request->validate([
                'tujuan_id' => 'required|exists:tujuans,id'
            ]);
            $tujuan_id = $request->tujuan_id;
        }


        if ($request->sasaran_id === 'add_new') {
            $request->validate([
                'new_sasaran' => 'required|string|max:50'
            ]);

            $sasaran = Sasaran::create(['nama' => $request->new_sasaran]);
            $sasaran_id = $sasaran->id;
        } else {
            $request->validate([
                'sasaran_id' => 'required|exists:sasarans,id'
            ]);
            $sasaran_id = $request->sasaran_id;
        }

        try {
            $filePath = $request->file('proposal')->store('proposals', 'public');

            Proker::create([
                'anggota_id' => Auth::user()->anggota->id,
                'judul' => $request->judul,
                'bidang_id' => $bidang_id,
                'jenis_id' => $jenis_id,
                'tujuan_id' => $tujuan_id,
                'sasaran_id' => $sasaran_id,
                'proposal' => $filePath,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('proker.index')
                ->with('success', 'Program kerja berhasil diajukan.');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
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

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
            'bidangs' => Bidang::all(),
            'jenisKegiatans' => JenisKegiatan::all(),
            'tujuans' => Tujuan::all(),
            'sasarans' => Sasaran::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'bidang_id' => 'required|exists:bidangs,id',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'tujuan_id' => 'required|exists:tujuans,id',
            'sasaran_id' => 'required|exists:sasarans,id',
            'proposal' => 'required|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $file = $request->file('proposal')->store('proposals', 'public');

        Proker::create([
            'anggota_id' => Auth::user()->anggota->id,
            'judul' => $request->judul,
            'bidang_id' => $request->bidang_id,
            'jenis_id' => $request->jenis_id,
            'tujuan_id' => $request->tujuan_id,
            'sasaran_id' => $request->sasaran_id,
            'proposal' => $file,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Program kerja berhasil diajukan.');
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
            'bidang_id' => 'required|exists:bidangs,id',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'tujuan_id' => 'required|exists:tujuans,id',
            'sasaran_id' => 'required|exists:sasarans,id',
            'proposal' => 'nullable|mimes:pdf|max:2048',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:pengajuan,disetujui,ditolak',
        ]);

        if ($request->hasFile('proposal')) {
            if ($proker->proposal && Storage::exists($proker->proposal)) {
                Storage::delete($proker->proposal);
            }
            $file = $request->file('proposal')->store('proposals');
            $proker->proposal = $file;
        }

        $proker->update([
            'judul' => $request->judul,
            'bidang_id' => $request->bidang_id,
            'jenis_id' => $request->jenis_id,
            'tujuan_id' => $request->tujuan_id,
            'sasaran_id' => $request->sasaran_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'proposal' => $proker->proposal,
        ]);

        return back()->with('success', 'Data program kerja berhasil diperbarui.');
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

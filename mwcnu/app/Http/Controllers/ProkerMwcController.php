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
use App\Models\JadwalProker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProkerMwcController extends Controller
{
    public function index()
    {
        $anggota   = Auth::user()->anggota;
        $status    = $anggota->status->status ?? null;
        $rantingId = $anggota->ranting_id ?? null;

        $prokers = Proker::with([
            'bidang',
            'jenis',
            'tujuan',
            'sasaran',
            'ranting',
            'anggota',
            'jadwalProker'
        ])
            ->whereHas('anggota.status', function ($s) {
                $s->where('status', 'MWC');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.prokermwc.index', compact('prokers', 'status', 'rantingId'));
    }

    public function create()
    {
        $status = Auth::user()->anggota->status->status ?? null;

        if ($status !== 'MWC') {
            abort(403, 'Anda tidak memiliki akses membuat Proker MWC.');
        }

        return view('pages.prokermwc.create', [
            'bidangs' => Bidang::orderBy('nama')->get(),
            'jenisKegiatans' => JenisKegiatan::orderBy('nama')->get(),
            'tujuans' => Tujuan::orderBy('nama')->get(),
            'sasarans' => Sasaran::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $anggota = Auth::user()->anggota;

        if ($anggota->status->status !== 'MWC') {
            abort(403, 'Anda tidak memiliki akses membuat Proker MWC.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'proposal' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($request->bidang_id === 'add_new') {
            $request->validate(['new_bidang' => 'required|string|max:50']);
            $bidang_id = Bidang::create(['nama' => $request->new_bidang])->id;
        } else {
            $request->validate(['bidang_id' => 'required|exists:bidangs,id']);
            $bidang_id = $request->bidang_id;
        }

        if ($request->jenis_id === 'add_new') {
            $request->validate(['new_jenis' => 'required|string|max:50']);
            $jenis_id = JenisKegiatan::create(['nama' => $request->new_jenis])->id;
        } else {
            $request->validate(['jenis_id' => 'required|exists:jenis_kegiatans,id']);
            $jenis_id = $request->jenis_id;
        }

        if ($request->tujuan_id === 'add_new') {
            $request->validate(['new_tujuan' => 'required|string|max:50']);
            $tujuan_id = Tujuan::create(['nama' => $request->new_tujuan])->id;
        } else {
            $request->validate(['tujuan_id' => 'required|exists:tujuans,id']);
            $tujuan_id = $request->tujuan_id;
        }

        if ($request->sasaran_id === 'add_new') {
            $request->validate(['new_sasaran' => 'required|string|max:50']);
            $sasaran_id = Sasaran::create(['nama' => $request->new_sasaran])->id;
        } else {
            $request->validate(['sasaran_id' => 'required|exists:sasarans,id']);
            $sasaran_id = $request->sasaran_id;
        }

        $proposalName = time() . '_' . $request->file('proposal')->getClientOriginalName();
        $proposalPath = $request->file('proposal')->storeAs('proposal-proker', $proposalName, 'public');

        Proker::create([
            'judul'      => $request->judul,
            'keterangan' => $request->keterangan,
            'anggota_id' => $anggota->id,
            'ranting_id' => null,
            'status'     => 'disetujui',

            'bidang_id'  => $bidang_id,
            'jenis_id'   => $jenis_id,
            'tujuan_id'  => $tujuan_id,
            'sasaran_id' => $sasaran_id,

            'proposal'   => $proposalPath,
        ]);

        return back()->with('success', 'Proker MWC berhasil dibuat.');
    }


    public function edit($id)
    {
        $anggota = Auth::user()->anggota;

        $proker = Proker::findOrFail($id);

        if ($anggota->status->status !== 'MWC') {
            abort(403, 'Akses ditolak.');
        }

        if ($proker->anggota->status->status !== 'MWC') {
            abort(403, 'Ini bukan Proker MWC.');
        }

        return view('pages.prokermwc.edit', [
            'proker' => $proker,
            'bidangs' => Bidang::all(),
            'jenisKegiatans' => JenisKegiatan::all(),
            'tujuans' => Tujuan::all(),
            'sasarans' => Sasaran::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $anggota = Auth::user()->anggota;
        $proker = Proker::findOrFail($id);

        if ($anggota->status->status !== 'MWC') {
            abort(403, 'Akses ditolak.');
        }

        if ($proker->anggota->status->status !== 'MWC') {
            abort(403, 'Ini bukan Proker MWC.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'proposal' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->bidang_id === 'add_new') {
            $request->validate(['new_bidang' => 'required']);
            $request->merge(['bidang_id' => Bidang::create(['nama' => $request->new_bidang])->id]);
        }

        if ($request->jenis_id === 'add_new') {
            $request->validate(['new_jenis' => 'required']);
            $request->merge(['jenis_id' => JenisKegiatan::create(['nama' => $request->new_jenis])->id]);
        }

        if ($request->tujuan_id === 'add_new') {
            $request->validate(['new_tujuan' => 'required']);
            $request->merge(['tujuan_id' => Tujuan::create(['nama' => $request->new_tujuan])->id]);
        }

        if ($request->sasaran_id === 'add_new') {
            $request->validate(['new_sasaran' => 'required']);
            $request->merge(['sasaran_id' => Sasaran::create(['nama' => $request->new_sasaran])->id]);
        }

        if ($request->hasFile('proposal')) {
            if ($proker->proposal && Storage::disk('public')->exists($proker->proposal)) {
                Storage::disk('public')->delete($proker->proposal);
            }

            $fileName = time() . '_' . $request->file('proposal')->getClientOriginalName();
            $filePath = $request->file('proposal')->storeAs('proposal-proker', $fileName, 'public');

            $proker->proposal = $filePath;
        }

        $proker->update([
            'judul'      => $request->judul,
            'keterangan' => $request->keterangan,
            'bidang_id'  => $request->bidang_id,
            'jenis_id'   => $request->jenis_id,
            'tujuan_id'  => $request->tujuan_id,
            'sasaran_id' => $request->sasaran_id,
            'proposal'   => $proker->proposal,
        ]);

        return back()->with('success', 'Proker MWC berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $anggota = Auth::user()->anggota;
        $proker = Proker::findOrFail($id);

        if ($anggota->status->status !== 'MWC') {
            abort(403, 'Akses ditolak.');
        }

        if ($proker->anggota->status->status !== 'MWC') {
            abort(403, 'Ini bukan Proker MWC.');
        }

        if ($proker->proposal && Storage::disk('public')->exists($proker->proposal)) {
            Storage::disk('public')->delete($proker->proposal);
        }

        $proker->delete();

        return back()->with('success', 'Proker MWC berhasil dihapus.');
    }
    public function pilih(Request $request, $id)
    {
        $anggota = Auth::user()->anggota;

        if ($anggota->status->status !== 'Ranting') {
            abort(403, 'Hanya anggota ranting yang bisa memilih proker.');
        }

        $request->validate([
            'estimasi_mulai'   => 'required|date',
            'estimasi_selesai' => 'required|date|after_or_equal:estimasi_mulai'
        ]);

        $proker = Proker::findOrFail($id);

        if ($proker->anggota->status->status !== 'MWC') {
            abort(403, 'Proker ini bukan Proker MWC.');
        }

        if ($proker->ranting_id !== null) {
            return back()->withErrors('Proker ini sudah dipilih oleh ranting lain.');
        }

        $bentrok = JadwalProker::where(function ($query) use ($request) {
            $query->whereBetween('estimasi_mulai', [$request->estimasi_mulai, $request->estimasi_selesai])
                ->orWhereBetween('estimasi_selesai', [$request->estimasi_mulai, $request->estimasi_selesai])
                ->orWhere(function ($q) use ($request) {
                    $q->where('estimasi_mulai', '<=', $request->estimasi_mulai)
                        ->where('estimasi_selesai', '>=', $request->estimasi_selesai);
                });
        })->exists();

        if ($bentrok) {
            return back()->withErrors('Tanggal tersebut sudah digunakan proker lain.');
        }

        DB::beginTransaction();

        try {

            $proker->update([
                'ranting_id' => $anggota->ranting_id,
                'status'     => 'pengajuan',
            ]);

            JadwalProker::create([
                'proker_id'            => $proker->id,
                'penanggung_jawab_id'  => $anggota->id,
                'estimasi_mulai'        => $request->estimasi_mulai,
                'estimasi_selesai'      => $request->estimasi_selesai,
                'status'                => 'penjadwalan'
            ]);

            DB::commit();

            return back()->with('success', 'Proker berhasil dipilih dan estimasi jadwal disimpan.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors('Terjadi kesalahan saat memilih proker.');
        }
    }

    public function disabledDates()
    {
        $dates = JadwalProker::join('prokers', 'jadwal_prokers.proker_id', '=', 'prokers.id')
            ->where('prokers.status', 'disetujui')
            ->select('jadwal_prokers.estimasi_mulai', 'jadwal_prokers.estimasi_selesai')
            ->get();

        $disabled = collect();

        foreach ($dates as $item) {
            $start = Carbon::parse($item->estimasi_mulai);
            $end   = Carbon::parse($item->estimasi_selesai);

            while ($start <= $end) {
                $disabled->push($start->format('Y-m-d'));
                $start->addDay();
            }
        }

        return response()->json($disabled->unique()->values());
    }
}

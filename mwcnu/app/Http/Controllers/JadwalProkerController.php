<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_proker;
use App\Models\Proker;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\Rule;

class JadwalProkerController extends Controller
{
    public function show()
    {
        $penjadwalan = Jadwal_proker::with('proker')->where('status', 'penjadwalan')->get();
        $berjalan = Jadwal_proker::with('proker')->where('status', 'berjalan')->get();
        $selesai = Jadwal_proker::with('proker')->where('status', 'selesai')->get();

        return view('pages.dashboard', compact('penjadwalan', 'berjalan', 'selesai'));
    }

    public function index()
    {
        $query = Jadwal_proker::with(['proker', 'penanggungJawab']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $query->orderBy('tanggal_mulai');
        $jadwals = $query->get();

        $belumDijadwalCount = Proker::where('status', 'di setujui')
        ->doesntHave('jadwal')
        ->count();

        return view('pages.jadwal_proker.index', compact('jadwals', 'belumDijadwalCount'));
    }

    public function create()
    {
        $prokers = Proker::where('status', 'di setujui')
            ->doesntHave('jadwal')
            ->get();

        return view('pages.jadwal_proker.create', compact('prokers'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'proker_id' => 'required|exists:prokers,id',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'status' => 'required|in:penjadwalan,berjalan,selesai',
                    'catatan' => 'nullable|string|max:100',
                ],
                [
                    'proker_id.required' => 'Silahkan pilih program kerja',
                    'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
                    'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
                    'status.required' => 'Status harus diisi',
                    'catatan.required' => 'Catatan harus diisi',
                ]
            );

            $proker = Proker::findOrFail($request->proker_id);

            Jadwal_proker::create([
                'proker_id' => $proker->id,
                'penanggung_jawab_id' => $proker->user_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);
            return redirect('jadwal/create')->with('success', 'Jadwal program kerja berhasil dibuat');
        } catch (Exception $e) {
            return redirect('jadwal/create')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $jadwal = Jadwal_proker::findOrFail($id);
        $prokers = Proker::all();
        return view('pages.jadwal_proker.edit', compact('jadwal', 'prokers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:penjadwalan,berjalan,selesai',
            'catatan' => 'nullable|string|max:100',
        ]);

        $jadwal = Jadwal_proker::findOrFail($id);
        $jadwal->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect("jadwal/{$id}")->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal_proker::findOrFail($id);
        $jadwal->delete();

        return redirect('/jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }
}

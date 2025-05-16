<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProkerController extends Controller
{
    public function proker_request()
    {
        $prokers = Proker::where('status', 'pengajuan')->get();

        return view('pages.programkerja.request', [
            'prokers' => $prokers,
        ]);
    }

    public function proker_approval(Request $request, $prokerId)
    {
        $request->validate([
            'for' => 'required|in:approve,reject',
        ]);

        $proker = Proker::findOrFail($prokerId);

        $proker->status = $request->for === 'approve' ? 'di setujui' : 'di tolak';
        $proker->save();

        $message = $request->for === 'approve'
            ? 'Program kerja berhasil disetujui'
            : 'Program kerja ditolak';

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

    public function index()
    {
        $query = Proker::query();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $prokers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.programkerja.index', compact('prokers'));
    }

    public function create()
    {
        return view('pages.programkerja.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'program' => 'required|string|max:100',
                'catatan' => 'required|string|max:100',
            ], [
                'program.required' => 'Program kerja harus diisi',
                'catatan.required' => 'Berikan alasan kenapa mengajukan program ini',
            ]);

            $proker = Proker::create([
                'user_id' => Auth::id(),
                'program' => $validated['program'],
                'catatan' => $validated['catatan'],
            ]);

            return redirect('proker/create')->with('success', 'Program kerja berhasil diajukan');
        } catch (\Exception $e) {
            return redirect('proker/create')->withErrors(['error'=> 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $proker = Proker::findOrFail($id);
        return view('pages.programkerja.edit', compact('proker'));
    }

    public function update(Request $request, $id)
    {
        $proker = Proker::findOrFail($id);

        $validated = $request->validate([
            'program' => 'required|string|max:100',
            'catatan' => 'required|string|max:100',
        ]);

        $proker->update([
            'program' => $validated['program'],
            'catatan' => $validated['catatan'],
        ]);

        return redirect("/proker/{$id}")->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prokers = Proker::findOrFail($id);
        $prokers->delete();

        return redirect('/proker')->with('success', 'Data program kerja berhasil dihapus');
    }
}

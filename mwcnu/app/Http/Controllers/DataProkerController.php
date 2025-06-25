<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\JenisKegiatan;
use App\Models\Sasaran;
use App\Models\Tujuan;

class DataProkerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $bidangs = Bidang::when($search, fn($q) => $q->where('nama', 'like', "%$search%"))->paginate(10, ['*'], 'bidangs_page');
        $jenisKegiatans = JenisKegiatan::when($search, fn($q) => $q->where('nama', 'like', "%$search%"))->paginate(10, ['*'], 'jenis_page');
        $tujuans = Tujuan::when($search, fn($q) => $q->where('nama', 'like', "%$search%"))->paginate(10, ['*'], 'tujuans_page');
        $sasarans = Sasaran::when($search, fn($q) => $q->where('nama', 'like', "%$search%"))->paginate(10, ['*'], 'sasarans_page');

        return view('pages.dataproker.index', compact(
            'bidangs',
            'jenisKegiatans',
            'tujuans',
            'sasarans',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bidang,jenis,tujuan,sasaran',
            'nama' => 'required|string|max:50'
        ]);

        switch ($request->type) {
            case 'bidang':
                Bidang::create(['nama' => $request->nama]);
                $msg = 'Bidang berhasil ditambahkan.';
                break;
            case 'jenis':
                JenisKegiatan::create(['nama' => $request->nama]);
                $msg = 'Jenis Kegiatan berhasil ditambahkan.';
                break;
            case 'tujuan':
                Tujuan::create(['nama' => $request->nama]);
                $msg = 'Tujuan berhasil ditambahkan.';
                break;
            case 'sasaran':
                Sasaran::create(['nama' => $request->nama]);
                $msg = 'Sasaran berhasil ditambahkan.';
                break;
            default:
                $msg = 'Tipe tidak valid.';
        }

        return back()->with('success', $msg);
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50'
        ]);

        $msg = '';
        switch ($type) {
            case 'bidang':
                Bidang::findOrFail($id)->update(['nama' => $request->nama]);
                $msg = 'Bidang berhasil diperbarui.';
                break;
            case 'jenis':
                JenisKegiatan::findOrFail($id)->update(['nama' => $request->nama]);
                $msg = 'Jenis Kegiatan berhasil diperbarui.';
                break;
            case 'tujuan':
                Tujuan::findOrFail($id)->update(['nama' => $request->nama]);
                $msg = 'Tujuan berhasil diperbarui.';
                break;
            case 'sasaran':
                Sasaran::findOrFail($id)->update(['nama' => $request->nama]);
                $msg = 'Sasaran berhasil diperbarui.';
                break;
            default:
                abort(404);
        }

        return redirect()->back()->with('success', $msg);
    }

    public function destroy($type, $id)
    {
        $msg = '';
        switch ($type) {
            case 'bidang':
                Bidang::destroy($id);
                $msg = 'Bidang berhasil dihapus.';
                break;
            case 'jenis':
                JenisKegiatan::destroy($id);
                $msg = 'Jenis Kegiatan berhasil dihapus.';
                break;
            case 'tujuan':
                Tujuan::destroy($id);
                $msg = 'Tujuan berhasil dihapus.';
                break;
            case 'sasaran':
                Sasaran::destroy($id);
                $msg = 'Sasaran berhasil dihapus.';
                break;
            default:
                abort(404);
        }

        return redirect()->back()->with('success', $msg);
    }
}

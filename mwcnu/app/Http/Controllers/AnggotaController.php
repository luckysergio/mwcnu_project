<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::all();

        return view('pages.anggota.index',[
            'anggotas' => $anggotas,
        ]);
    }

    public function create()
    {
        return view('pages.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:100'],
            'phone' => ['required','min:10', 'max:15'],
            'jabatan' => ['required', Rule::in(['mustasyar','syuriyah','ross syuriah','katib','awan','tanfidiyah','wakil ketua','sekertaris','bendahara'])],
            'ranting' => ['required', Rule::in(['karang tengah','karang mulya','karang timur','pedurenan','pondok bahar','pondok pucung','parung jaya'])],
            'status' => ['required', Rule::in(['active', 'in active'])],
        ]);

        Anggota::create($request->validated());

        return with('success', 'Berhasil menambah data');
    }

    public function edit($id)
    {
        $anggotas = Anggota::findOrFail($id);
        return view('pages.anggota.edit',[
            'anggotas' => $anggotas,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:100'],
            'phone' => ['required','min:10', 'max:15'],
            'jabatan' => ['required', Rule::in(['mustasyar','syuriyah','ross syuriah','katib','awan','tanfidiyah','wakil ketua','sekertaris','bendahara'])],
            'ranting' => ['required', Rule::in(['karang tengah','karang mulya','karang timur','pedurenan','pondok bahar','pondok pucung','parung jaya'])],
            'status' => ['required', Rule::in(['active', 'in active'])],
        ]);

        Anggota::findOrFail($id)->update($request->validated());

        return with('success', 'Berhasil mengubah data');
    }

    public function destroy($id)
    {
        $anggotas = Anggota::findOrFail($id);
        $anggotas->delete();

        return redirect('/anggota')->with('success','Data berhasil dihapus');
    }
}

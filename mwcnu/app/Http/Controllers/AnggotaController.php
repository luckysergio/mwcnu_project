<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::all();

        return view('pages.anggota.index', [
            'anggotas' => $anggotas,
        ]);
    }

    public function create()
    {
        return view('pages.anggota.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string|max:100',
                    'email' => 'required|string|email|max:100|unique:anggotas',
                    'phone' => 'required|string|min:10|max:15',
                    'jabatan' => 'required|in:mustasyar,syuriyah,ross syuriah,katib,awan,tanfidiyah,wakil ketua,sekertaris,bendahara,anggota',
                    'ranting' => 'required|in:karang tengah,karang mulya,karang timur,pedurenan,pondok bahar,pondok pucung,parung jaya',
                    'status' => 'required|in:active,inactive',
                ],
                [
                    'name.required' => 'Nama harus diisi',
                    'email.required' => 'Email harus diisi',
                    'email.unique' => 'Email sudah terdaftar',
                    'phone.required' => 'Nomor Handphone harus diisi',
                    'jabatan.required' => 'Jabatan harus dipilih',
                    'ranting.required' => 'ranting harus dipilih',
                    'status.required' => 'status harus dipilih',
                ]
            );

            $anggota = Anggota::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'jabatan' => $request->jabatan,
                'ranting' => $request->ranting,
                'status' => $request->status,
            ]);

            return redirect('anggota/create')->with('success', 'Berhasil menambahkan data');
        } catch (Exception $e) {
            return redirect('anggota/create')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('pages.anggota.edit', [
            'anggota' => $anggota,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:anggotas,email,' . $id,
                'phone' => 'required|string|min:10|max:15',
                'jabatan' => 'required|in:mustasyar,syuriah,ross syuriah,katib,awan,tanfidiyah,wakil ketua,sekertaris,bendahara,anggota',
                'ranting' => 'required|in:karang tengah,karang mulya,karang timur,pedurenan,pondok bahar,pondok pucung,parung jaya',
                'status' => 'required|in:active,inactive',
            ]);

            $anggota->update($validatedData);

            return redirect("/anggota/{$id}")->with('success', 'Berhasil mengubah data');
        } catch (Exception $e) {
            return redirect("/anggota/{$id}")->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $anggotas = Anggota::findOrFail($id);
        $anggotas->delete();

        return redirect('/anggota')->with('success', 'Data berhasil dihapus');
    }
}

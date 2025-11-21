<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Ranting;
use Illuminate\Http\Request;

class JabatanRantingController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $rantings = Ranting::all();
        return view('pages.jdr.index', compact('roles', 'rantings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:jabatan,ranting',
            'nama' => 'required|string|max:50',
        ]);

        if ($request->type === 'jabatan') {
            Role::create(['jabatan' => $request->nama]);
        } else {
            Ranting::create(['kelurahan' => $request->nama]);
        }

        return back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate(['nama' => 'required|string|max:50']);

        if ($type === 'jabatan') {
            Role::findOrFail($id)->update(['jabatan' => $request->nama]);
        } else {
            Ranting::findOrFail($id)->update(['kelurahan' => $request->nama]);
        }

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'jabatan') {
            Role::destroy($id);
        } else {
            Ranting::destroy($id);
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }
}

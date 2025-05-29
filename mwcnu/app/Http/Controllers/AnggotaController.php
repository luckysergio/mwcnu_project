<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index()
    {
        $query = Anggota::query();

        if (request('ranting')) {
            $query->where('ranting', request('ranting'));
        }

        $anggotas = $query->orderByRaw("FIELD(jabatan, 'mustasyar','syuriyah','ross syuriah','katib','awan','tanfidiyah','wakil ketua','sekertaris','bendahara','anggota')")
            ->get();

        return view('pages.anggota.index', compact('anggotas'));
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
                    // 'email' => 'required|string|email|max:100|unique:anggotas',
                    'phone' => 'required|string|min:10|max:15',
                    'jabatan' => 'required|in:mustasyar,syuriyah,ross syuriah,katib,awan,tanfidiyah,wakil ketua,sekertaris,bendahara,anggota',
                    'ranting' => 'required|in:karang tengah,karang mulya,karang timur,pedurenan,pondok bahar,pondok pucung,parung jaya',
                    'status' => 'required|in:active,inactive',
                ],
                [
                    'name.required' => 'Nama harus diisi',
                    // 'email.required' => 'Email harus diisi',
                    // 'email.unique' => 'Email sudah terdaftar',
                    'phone.required' => 'Nomor Handphone harus diisi',
                    'jabatan.required' => 'Jabatan harus dipilih',
                    'ranting.required' => 'ranting harus dipilih',
                    'status.required' => 'status harus dipilih',
                ]
            );

            $anggota = Anggota::create([
                'name' => $request->name,
                // 'email' => $request->email,
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
        $anggota = Anggota::with('user')->findOrFail($id);

        $users = $anggota->user ? collect() : User::whereDoesntHave('anggota')->get();

        return view('pages.anggota.edit', [
            'anggota' => $anggota,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $anggota = Anggota::with('user')->findOrFail($id);

            $rules = [
                'name' => 'required|string|max:100',
                'phone' => 'required|string|min:10|max:15',
                'jabatan' => 'required|in:mustasyar,syuriah,ross syuriah,katib,awan,tanfidiyah,wakil ketua,sekertaris,bendahara,anggota',
                'ranting' => 'required|in:karang tengah,karang mulya,karang timur,pedurenan,pondok bahar,pondok pucung,parung jaya',
                'status' => 'required|in:active,inactive',
            ];

            if ($anggota->user) {
                $rules += [
                    'user_name' => 'required|string|max:100',
                    'user_email' => 'required|email|unique:users,email,' . $anggota->user->id,
                    'user_password' => 'nullable|string|min:6',
                ];
            } else {
                $rules['user_id'] = 'required|exists:users,id';
            }

            $validatedData = $request->validate($rules);

            $anggota->update([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'jabatan' => $validatedData['jabatan'],
                'ranting' => $validatedData['ranting'],
                'status' => $validatedData['status'],
            ]);

            if ($anggota->user) {
                $userUpdate = [
                    'name' => $validatedData['user_name'],
                    'email' => $validatedData['user_email'],
                ];

                if (!empty($validatedData['user_password'])) {
                    $userUpdate['password'] = Hash::make($validatedData['user_password']);
                }

                $anggota->user->update($userUpdate);
            } else {
                $user = User::findOrFail($validatedData['user_id']);

                if ($user->anggota) {
                    return redirect("/anggota/{$id}")->withErrors(['user_id' => 'User sudah tertaut ke anggota lain.']);
                }

                $anggota->user_id = $user->id;
                $anggota->save();

                $user->status = 'approved';
                $user->save();
            }

            return redirect("/anggota/{$id}")->with('success', 'Berhasil mengubah data anggota.');
        } catch (Exception $e) {
            return redirect("/anggota/{$id}")->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function linkUserForm($anggotaId)
    {
        $anggota = Anggota::findOrFail($anggotaId);

        $availableUsers = User::whereDoesntHave('anggota')->get();

        return view('pages.anggota.link-user', [
            'anggota' => $anggota,
            'users' => $availableUsers,
        ]);
    }

    public function linkUser(Request $request, $anggotaId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $anggota = Anggota::findOrFail($anggotaId);

        $user = User::findOrFail($request->user_id);
        if ($user->anggota) {
            return redirect()->back()->withErrors(['user_id' => 'User ini sudah tertaut dengan anggota lain.']);
        }

        $anggota->update(['user_id' => $user->id]);

        return redirect("/anggota/{$anggotaId}")->with('success', 'Berhasil menautkan akun ke anggota.');
    }

    public function destroy($id)
    {
        $anggotas = Anggota::findOrFail($id);
        $anggotas->delete();

        return redirect('/anggota')->with('success', 'Data berhasil dihapus');
    }
}

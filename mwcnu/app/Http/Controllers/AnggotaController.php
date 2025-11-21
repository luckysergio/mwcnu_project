<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Ranting;
use App\Models\Role;
use App\Models\User;
use App\Models\AnggotaStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $rantings = Ranting::orderBy('kelurahan')->get();

        $statusList = AnggotaStatus::orderBy('status')->get();

        $query = Anggota::with(['user', 'role', 'ranting', 'status'])
            ->select('anggotas.*')
            ->join('roles', 'anggotas.role_id', '=', 'roles.id')
            ->orderBy('roles.jabatan');


        if ($request->has('status') && $request->status != '') {
            $query->whereHas('status', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }


        if ($request->status == 'Ranting' && $request->ranting) {
            $query->whereHas('ranting', function ($q) use ($request) {
                $q->where('kelurahan', $request->ranting);
            });
        }

        $anggotas = $query->paginate(10);

        return view('pages.anggota.index', compact(
            'anggotas',
            'rantings',
            'statusList'
        ));
    }

    public function create()
    {
        $roles = Role::orderBy('jabatan')->get();
        $rantings = Ranting::orderBy('kelurahan')->get();
        $statuses = AnggotaStatus::orderBy('status')->get();

        return view('pages.anggota.create', compact('roles', 'rantings', 'statuses'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'           => 'required|string|max:100',
                'email'          => 'required|email|unique:users,email',
                'password'       => 'required|string|min:6',
                'phone'          => 'required|regex:/^[0-9]+$/|min:10|max:15',
                'role_id'        => 'required',
                'ranting_id'     => 'nullable',
                'status_id'      => 'required|exists:anggota_statuses,id',
                'new_role'       => 'nullable|string|max:50',
                'new_ranting'    => 'nullable|string|max:50',
            ]);

            $status = AnggotaStatus::find($request->status_id);
            if (!$status) {
                return back()->withErrors(['status_id' => 'Status anggota tidak valid.']);
            }

            $statusNameLower = strtolower($status->status);

            if ($statusNameLower !== 'mwc') {
                if (!$request->filled('ranting_id')) {
                    return back()->withErrors(['ranting_id' => 'Ranting harus dipilih untuk status ini.'])->withInput();
                }

                if ($request->ranting_id === 'new') {
                    if (!$request->new_ranting) {
                        return back()->withErrors(['new_ranting' => 'Nama ranting baru harus diisi'])->withInput();
                    }
                    $ranting = Ranting::create(['kelurahan' => $request->new_ranting]);
                    $ranting_id = $ranting->id;
                } else {
                    $ranting_id = $request->ranting_id;
                }
            } else {
                $ranting_id = null;
            }

            if ($request->role_id === 'new') {
                if (!$request->new_role) {
                    return back()->withErrors(['new_role' => 'Nama role baru harus diisi'])->withInput();
                }
                $role = Role::create(['jabatan' => $request->new_role]);
                $role_id = $role->id;
            } else {
                $role_id = $request->role_id;
            }

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Anggota::create([
                'name'       => $request->name,
                'phone'      => $request->phone,
                'status_id'  => $request->status_id,
                'user_id'    => $user->id,
                'role_id'    => $role_id,
                'ranting_id' => $ranting_id,
            ]);

            return back()->with('success', 'Berhasil menambahkan data anggota');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);
        $roles = Role::orderBy('jabatan')->get();
        $rantings = Ranting::orderBy('kelurahan')->get();
        $statuses = AnggotaStatus::orderBy('status')->get();

        return view('pages.anggota.edit', compact('anggota', 'roles', 'rantings', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        $mwcId = AnggotaStatus::where('status', 'MWC')->value('id');

        // VALIDASI -------------------------------------------------------
        $rules = [
            'name'        => 'required|string|max:100',
            'phone'       => 'required|regex:/^[0-9]+$/|min:10|max:15',
            'status_id'   => 'required|exists:anggota_statuses,id',
            'role_id'     => 'required',
            'new_role'    => 'nullable|string|max:50',
            'new_ranting' => 'nullable|string|max:50',
            'user_email'  => $anggota->user ? [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($anggota->user->id),
            ] : 'nullable|email|unique:users,email',
            'user_password' => 'nullable|string|min:6',
            'user_id'        => !$anggota->user ? 'nullable|exists:users,id' : 'nullable'
        ];

        if ($request->status_id != $mwcId) {
            $rules['ranting_id'] = 'required';
        }

        $request->validate($rules);

        if ($request->role_id === 'new') {
            if (!$request->new_role) {
                return back()->withErrors(['new_role' => 'Nama role baru harus diisi']);
            }
            $role = Role::create(['jabatan' => $request->new_role]);
            $role_id = $role->id;
        } else {
            $role_id = $request->role_id;
        }

        if ($request->status_id == $mwcId) {
            $ranting_id = null;
        } else {
            if ($request->ranting_id === 'new') {
                if (!$request->new_ranting) {
                    return back()->withErrors(['new_ranting' => 'Nama ranting baru harus diisi']);
                }
                $ranting = Ranting::create(['kelurahan' => $request->new_ranting]);
                $ranting_id = $ranting->id;
            } else {
                $ranting_id = $request->ranting_id;
            }
        }

        if ($anggota->user) {

            $anggota->user->email = $request->user_email;

            if ($request->filled('user_password')) {
                $anggota->user->password = Hash::make($request->user_password);
            }

            $anggota->user->save();
        } elseif ($request->filled('user_id')) {
            $anggota->user_id = $request->user_id;
        }

        $anggota->update([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'status_id'  => $request->status_id,
            'role_id'    => $role_id,
            'ranting_id' => $ranting_id,
        ]);

        return back()->with('success', 'Data anggota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        if ($anggota->user) {
            $anggota->user->delete();
        }

        $anggota->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}

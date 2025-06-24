<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Ranting;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $rantings = Ranting::orderBy('kelurahan')->get();

        $query = Anggota::select('anggotas.*')
            ->with(['user', 'role', 'ranting'])
            ->join('roles', 'anggotas.role_id', '=', 'roles.id')
            ->orderBy('roles.jabatan');

        if ($request->ranting) {
            $query->whereHas('ranting', function ($q) use ($request) {
                $q->where('kelurahan', $request->ranting);
            });
        }

        $anggotas = $query->paginate(10);

        return view('pages.anggota.index', compact('anggotas', 'rantings'));
    }

    public function create()
    {
        $roles = Role::orderBy('jabatan')->get();
        $rantings = Ranting::orderBy('kelurahan')->get();

        return view('pages.anggota.create', compact('roles', 'rantings'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'phone' => 'required|string|min:10|max:15',
                'role_id' => 'required',
                'ranting_id' => 'required',
                'status' => 'required|in:active,inactive',
                'new_role' => 'nullable|string|max:50',
                'new_ranting' => 'nullable|string|max:50',
            ]);

            if ($request->role_id === 'new') {
                if (!$request->new_role) {
                    return back()->withErrors(['new_role' => 'Nama role baru harus diisi']);
                }
                $role = Role::create(['jabatan' => $request->new_role]); // ✅ FIX
                $role_id = $role->id;
            } else {
                $role_id = $request->role_id;
            }

            if ($request->ranting_id === 'new') {
                if (!$request->new_ranting) {
                    return back()->withErrors(['new_ranting' => 'Nama ranting baru harus diisi']);
                }
                $ranting = Ranting::create(['kelurahan' => $request->new_ranting]); // ✅ FIX
                $ranting_id = $ranting->id;
            } else {
                $ranting_id = $request->ranting_id;
            }

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Anggota::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'status' => $request->status,
                'user_id' => $user->id,
                'role_id' => $role_id,
                'ranting_id' => $ranting_id,
            ]);

            return back()->with('success', 'Berhasil menambahkan anggota, user, role/ranting jika baru.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);
        $roles = Role::orderBy('jabatan')->get();
        $rantings = Ranting::orderBy('kelurahan')->get();

        return view('pages.anggota.edit', compact('anggota', 'roles', 'rantings'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|min:10|max:15',
            'status' => 'required|in:active,inactive',
            'role_id' => 'required',
            'ranting_id' => 'required',
            'new_role' => 'nullable|string|max:50',
            'new_ranting' => 'nullable|string|max:50',
            'user_email' => $anggota->user ? [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($anggota->user->id),
            ] : 'nullable|email|unique:users,email',
            'user_password' => 'nullable|string|min:6',
            'user_id' => !$anggota->user ? 'nullable|exists:users,id' : 'nullable'
        ]);

        if ($request->role_id === 'new') {
            if (!$request->new_role) {
                return back()->withErrors(['new_role' => 'Nama role baru harus diisi']);
            }
            $role = Role::create(['jabatan' => $request->new_role]);
            $role_id = $role->id;
        } else {
            $role_id = $request->role_id;
        }

        if ($request->ranting_id === 'new') {
            if (!$request->new_ranting) {
                return back()->withErrors(['new_ranting' => 'Nama ranting baru harus diisi']);
            }
            $ranting = Ranting::create(['kelurahan' => $request->new_ranting]);
            $ranting_id = $ranting->id;
        } else {
            $ranting_id = $request->ranting_id;
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
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => $request->status,
            'role_id' => $role_id,
            'ranting_id' => $ranting_id,
        ]);

        return back()->with('success', 'Data anggota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}

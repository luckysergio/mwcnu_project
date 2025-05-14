<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function account_request()
    {
        $users = User::where('status', 'submitted')->get();
        $anggotas = Anggota::where('user_id', null)->get();

        return view('pages.account-request.index', [
            'users' => $users,
            'anggotas' => $anggotas,
        ]);
    }

    public function countSubmittedUsers()
    {
        $count = User::where('status', 'submitted')->count();
        return response()->json(['count' => $count]);
    }


    public function account_approval(Request $request, $userId)
    {
        $request->validate([
            'anggota_id' => 'nullable',
            'exists:anggotas,id'
        ]);

        $for = $request->input('for');

        $user = User::findOrFail($userId);
        $user->status = $for == 'approve' ? 'approved' : 'rejected';
        $user->save();

        $anggotaId = $request->input('anggota_id');

        if ($request->has('anggota_id') && isset($anggotaId)) {
            Anggota::where('id', $anggotaId)->update([
                'user_id' => $user->id,
            ]);
        }

        return back()->with('success', $for == 'approve' ? 'Akun berhasil disetujui' : 'Akun berhasil ditolak');
    }

    public function profile_view()
    {
        return view('pages.profile.index');
    }

    public function profile_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'user_name' => 'required|string|max:100',
            'user_email' => 'required|email|unique:users,email,' . $user->id,
            'user_password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $validated['user_name'],
            'email' => $validated['user_email'],
        ];

        if (!empty($validated['user_password'])) {
            $data['password'] = Hash::make($validated['user_password']);
        }

        $user->update($data);

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}

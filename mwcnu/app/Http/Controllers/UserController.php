<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function account_approval(Request $request, $userId)
    {
        $request->validate([
            'anggota_id' => 'nullable', 'exists:anggotas,id'
        ]);

        $for = $request->input('for');

        $user = User::findOrFail($userId);
        $user->status = $for == 'approve' ? 'approved' : 'rejected';
        $user->save();

        $anggotaId = $request->input('anggota_id');

        if($request->has('anggota_id') && isset($anggotaId))
        {
            Anggota::where('id', $anggotaId)->update([
                'user_id' => $user->id,
            ]);
        }

        return back()->with('success', $for == 'approve' ? 'Akun berhasil disetujui' : 'Akun berhasil ditolak');
    }
}

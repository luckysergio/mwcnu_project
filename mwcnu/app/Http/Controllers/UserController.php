<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile_view()
    {
        return view('pages.profile.index');
    }

    public function profile_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'user_email' => 'required|email|unique:users,email,' . $user->id,
            'user_password' => 'nullable|string|min:6',
        ]);

        $data = [
            'email' => $validated['user_email'],
        ];

        if (!empty($validated['user_password'])) {
            $data['password'] = Hash::make($validated['user_password']);
        }

        $user->update($data);

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan untuk mengimpor model User jika belum diimpor

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::whereNot('role', 'admin')->get(); // Mendapatkan semua data user
        return view('admin.master-user.index',compact('users'));
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan data user baru dari $request
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Mendapatkan data user berdasarkan ID
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Logika untuk mengupdate data user berdasarkan $id dari $request
    }

    public function delete($id)
    {
        $user = User::findOrFail($id); // Mendapatkan data user berdasarkan ID
        $user->delete(); // Menghapus data user
        return redirect()->route('setting.users')->with('success', 'User berhasil dihapus');
    }
}

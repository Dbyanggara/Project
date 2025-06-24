<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kantin;
use App\Models\User;

class KantinController extends Controller
{
    public function create()
    {
        // Mengambil semua user dengan role 'seller' untuk dropdown di form
        $penjuals = User::role('seller')->orderBy('name')->get();
        return view('admin.kantins.create', compact('penjuals'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Disesuaikan dengan form input 'name'
            'user_id' => 'required|exists:users,id', // Disesuaikan dengan form input 'user_id'
            'location' => 'required|string|max:255', // Disesuaikan dengan form input 'location'
        ]);

        Kantin::create([
            'name' => $validatedData['name'],
            'user_id' => $validatedData['user_id'],
            'location' => $validatedData['location'],
        ]);

        return redirect()->route('admin.kantins.index')->with('success', 'Kantin berhasil ditambahkan.');
    }

    public function index()
    {
        // Mengambil data kantin dengan paginasi, 15 item per halaman
        // Eager load relasi 'user' untuk menampilkan nama penjual secara efisien
        $kantins = Kantin::with('user')->orderBy('name')->paginate(15);

        return view('admin.kantins.index', compact('kantins'));
    }

    public function edit(Kantin $kantin)
    {
        // Mengambil semua user dengan role 'seller' untuk dropdown di form
        $penjuals = User::role('seller')->orderBy('name')->get();
        return view('admin.kantins.edit', compact('kantin', 'penjuals'));
    }

    public function update(Request $request, Kantin $kantin)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'location' => 'required|string|max:255',
        ]);

        $kantin->update([
            'name' => $validatedData['name'],
            'user_id' => $validatedData['user_id'],
            'location' => $validatedData['location'],
        ]);

        return redirect()->route('admin.kantins.index')->with('success', 'Kantin berhasil diperbarui.');
    }

    public function destroy(Kantin $kantin)
    {
        $kantin->delete();
        return redirect()->route('admin.kantins.index')->with('success', 'Kantin berhasil dihapus.');
    }
}

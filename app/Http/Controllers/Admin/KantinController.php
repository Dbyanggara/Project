<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kantin;

class KantinController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'penjual_id' => 'required|exists:users,id',
            'lokasi' => 'required|string|max:255',
        ]);
        $penjual = \App\Models\User::find($request->penjual_id);
        \App\Models\Kantin::create([
            'name' => $request->nama,
            'location' => $request->lokasi,
            // Jika ingin simpan nama penanggung jawab, tambahkan field di tabel dan model
        ]);
        return redirect()->route('admin.sellers.index')->with('success', 'Kantin berhasil ditambahkan.');
    }

    public function index()
    {
        $kantins = \App\Models\Kantin::orderBy('name')->get();
        $penjuals = \App\Models\User::role('seller')->orderBy('name')->get();
        return view('admin.seller.index', compact('kantins', 'penjuals'));
    }
}

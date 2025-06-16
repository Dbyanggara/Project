<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $seller = Auth::user();

        if (!$seller->kantin) {
            // Membuat instance LengthAwarePaginator meskipun tidak ada item
            $menus = new \Illuminate\Pagination\LengthAwarePaginator(
                $items = [],
                $total = 0,
                $perPage = 10,
                $currentPage = null,
                $options = []
            );
            return view('seller.menus.index', ['menus' => $menus, 'seller' => $seller])->with('warning', 'Anda belum terdaftar memiliki kantin. Silakan hubungi admin.');
        }
        $menus = Menu::where('kantin_id', $seller->kantin->id)->latest()->paginate(10);
        return view('seller.menus.index', compact('menus', 'seller'));
    }

    public function create()
    {
        $seller = Auth::user();

        if (!$seller->kantin) {
            return redirect()->route('seller.menus.index')->with('error', 'Anda harus memiliki kantin untuk menambah menu.');
        }

        return view('seller.menus.create');
    }

    public function store(Request $request)
    {
        $seller = Auth::user();
        if (!$seller->kantin) {
            return redirect()->route('seller.menus.index')->with('error', 'Operasi gagal: Kantin tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/menus');
            $imagePath = str_replace('public/', '', $imagePath);

        }
        Menu::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,

            'kantin_id' => $seller->kantin->id,
        ]);
        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }


    public function edit(Menu $menu)
    {
        $seller = Auth::user();
        if (!$seller->kantin || $menu->kantin_id !== $seller->kantin->id) {
            abort(403, 'Akses ditolak.');
        }
        return view('seller.menus.edit', compact('menu', 'seller'));
    }

    public function update(Request $request, Menu $menu)
    {
        $seller = Auth::user();
        if (!$seller->kantin || $menu->kantin_id !== $seller->kantin->id) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $menuData = $request->only(['name', 'price', 'stock']);
        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::delete('public/' . $menu->image);
            }
            $imagePath = $request->file('image')->store('public/menus');
            $menuData['image'] = str_replace('public/', '', $imagePath);
        }
         $menu->update($menuData);
        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $seller = Auth::user();
        if (!$seller->kantin || $menu->kantin_id !== $seller->kantin->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($menu->image) {
            Storage::delete('public/' . $menu->image);
        }

        $menu->delete();
        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}

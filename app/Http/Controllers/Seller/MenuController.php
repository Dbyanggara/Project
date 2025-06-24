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
        // Instantiate a new Menu object for the create form
        $menu = new Menu();
        return view('seller.menus.create', compact('menu', 'seller'));
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
            try {
                // Buat direktori jika belum ada
                if (!Storage::disk('public')->exists('menus')) {
                    Storage::disk('public')->makeDirectory('menus');
                }

                // Simpan gambar dengan nama unik
                $imagePath = $request->file('image')->store('menus', 'public');

                if (!$imagePath) {
                    return redirect()->back()->with('error', 'Gagal mengunggah gambar. Silakan coba lagi.');
                }

                // Log untuk debugging
                \Log::info('Image uploaded successfully', [
                    'path' => $imagePath,
                    'full_path' => Storage::disk('public')->path($imagePath),
                    'url' => Storage::disk('public')->url($imagePath)
                ]);
            } catch (\Exception $e) {
                \Log::error('Error uploading image: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah gambar: ' . $e->getMessage());
            }
        }

        try {
            Menu::create([
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $imagePath,
                'kantin_id' => $seller->kantin->id,
            ]);
            return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi error, hapus gambar yang sudah diupload
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            \Log::error('Error creating menu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan menu: ' . $e->getMessage());
        }
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
            try {
                // Hapus gambar lama jika ada
                if ($menu->image) {
                    Storage::disk('public')->delete($menu->image);
                }

                // Upload gambar baru
                $imagePath = $request->file('image')->store('menus', 'public');

                if (!$imagePath) {
                    return redirect()->back()->with('error', 'Gagal mengunggah gambar. Silakan coba lagi.');
                }

                $menuData['image'] = $imagePath;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah gambar. Silakan coba lagi.');
            }
        }

        try {
            $menu->update($menuData);
            return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil diperbarui.');
        } catch (\Exception $e) {
            // Jika terjadi error saat update, hapus gambar baru jika ada
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui menu. Silakan coba lagi.');
        }
    }

    public function destroy(Menu $menu)
    {
        $seller = Auth::user();
        if (!$seller->kantin || $menu->kantin_id !== $seller->kantin->id) {
            abort(403, 'Akses ditolak.');
        }
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image); // Hapus gambar dari disk public
        }

        $menu->delete();
        return redirect()->route('seller.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}

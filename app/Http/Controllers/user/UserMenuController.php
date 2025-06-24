<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Kantin;
use App\Models\Menu;

class UserMenuController extends Controller
{
    /**
     * Menampilkan daftar kantin.
     */
    public function index(): View
    {
        $kantins = Kantin::with('user')->get();
        return view('user.menu', compact('kantins'));
    }

    /**
     * Menampilkan detail menu kantin.
     */
    public function showMenu($id): View
    {
        $kantin = Kantin::with(['menus', 'user'])->findOrFail($id);
        return view('user.kantin.menu_detail', compact('kantin'));
    }

    /**
     * Menampilkan detail menu spesifik.
     */
    public function showMenuDetail($menu_id): View
    {
        $menu = Menu::with(['kantin.user'])->findOrFail($menu_id);
        return view('user.kantin.menu_detail', compact('menu'));
    }
}

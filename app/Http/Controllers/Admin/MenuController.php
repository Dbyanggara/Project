<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('seller')->latest()->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }
    // Tambahkan method create, store, edit, update, destroy sesuai kebutuhan
}

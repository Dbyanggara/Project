<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        dd(auth()->user());
        return view('dashboard.admin');
    }
}

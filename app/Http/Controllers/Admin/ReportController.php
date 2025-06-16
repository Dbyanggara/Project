<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logika untuk mengambil dan memproses data laporan bisa ditambahkan di sini
        return view('admin.reports.index');
    }
}

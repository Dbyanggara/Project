<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the seller's profile form.
     *
     * Ini akan menggunakan view seller.profile.profile
     * Logika untuk update profil, password, dan hapus akun akan tetap
     * menggunakan route dan controller bawaan Breeze (ProfileController global)
     * karena fungsionalitasnya sama.
     */
    public function edit(Request $request): View
    {
        return view('seller.profile.profile', [
            'user' => $request->user(),
        ]);
    }
}

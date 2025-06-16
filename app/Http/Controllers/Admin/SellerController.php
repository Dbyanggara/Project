<?php

namespace App\Http\Controllers\Admin;

   use App\Http\Controllers\Controller;
   use App\Models\User; // Import model User
   use Illuminate\Http\Request;

   class SellerController extends Controller
   {
       public function index()
       {
           return redirect()->route('admin.kantins.index');
       }
       // Anda bisa menambahkan metode lain untuk CRUD penjual jika diperlukan (create, store, edit, update, destroy)
   }

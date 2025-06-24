<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the seller's chat interface.
     */
    public function index(Request $request): View
    {
        $currentSeller = Auth::user();
        if (!$currentSeller || !$currentSeller->hasRole('seller')) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $chatWithUser = null;
        $chatWithUserId = $request->query('user_id');
        $chatWithUserName = $request->query('user_name');

        if ($chatWithUserId) {
            $chatWithUser = \App\Models\User::find($chatWithUserId);
            if ($chatWithUser && !$chatWithUser->hasRole('user')) {
                $chatWithUser = null;
            }
            if ($chatWithUser && !$chatWithUserName) {
                $chatWithUserName = $chatWithUser->name;
            }
            if (!$chatWithUser) {
                $chatWithUserId = null;
                $chatWithUserName = null;
            }
        }

        return view('seller.chat.index', [
            'chatWithUser' => $chatWithUser,
            'chatWithUserId' => $chatWithUserId,
            'chatWithUserName' => $chatWithUserName,
        ]);
    }
}

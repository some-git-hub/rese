<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class MypageController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $reservations = Reservation::where('user_id', auth()->id())
            ->where('status', 0) // 0: 予約中
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        $favoriteShops = $user->favorites()->get();

        return view('mypage.show', compact('user', 'reservations', 'favoriteShops'));
    }
}

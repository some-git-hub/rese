<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class MypageController extends Controller
{
    /**
     * マイページの表示
     */
    public function show()
    {
        $user = auth()->user();

        // 予約情報一覧
        $reservations = Reservation::where('user_id', auth()->id())
            ->where('status', 0) // 0: 予約中
            ->whereHas('shop.user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        // お気に入り店舗一覧
        $favoriteShops = $user->favorites()
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get();


        return view('mypage.show', compact('user', 'reservations', 'favoriteShops'));
    }
}

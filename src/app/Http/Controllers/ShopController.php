<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;

class ShopController extends Controller
{
    /**
     * 飲食店一覧画面の表示
     */
    public function index(Request $request)
    {
        $query = Shop::whereHas('user', function ($q) {
            $q->whereNull('deleted_at');
        });

        if ($request->region) {
            $query->where('region', $request->region);
        }

        if ($request->genre) {
            $query->where('genre', $request->genre);
        }

        if ($request->keyword) {
            $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        $shops = $query->get();

        $regions = Shop::select('region')->distinct()->pluck('region')->filter();
        $genres = Shop::select('genre')->distinct()->pluck('genre')->filter();

        return view('shop.index', compact('shops', 'regions', 'genres'));
    }


    /**
     * 飲食店詳細画面の表示
     */
    public function show(Shop $shop)
    {
        $user = auth()->user();
        $pendingReview = null;

        if ($user) {
            $pendingReview = Reservation::where('user_id', $user->id)
                ->where('shop_id', $shop->id)
                ->where('status', 1) // 来店済み
                ->where('rating', 0) // 未評価
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->first();

            if ($pendingReview) {
                Reservation::where('user_id', $user->id)
                    ->where('shop_id', $shop->id)
                    ->where('status', 1)
                    ->where('rating', 0)
                    ->where('id', '!=', $pendingReview->id)
                    ->update(['rating' => -1]);
            }
        }

        $from = request()->query('from');

        return view('shop.show', compact('shop', 'pendingReview', 'from'));
    }
}

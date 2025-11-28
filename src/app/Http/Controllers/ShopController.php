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
    $query = Shop::query();

    // エリアで絞り込み（部分一致でもOK）
    if ($request->region) {
        $query->where('region', $request->region); // 完全一致
        // 部分一致なら → $query->where('region', 'like', '%'.$request->region.'%');
    }

    // ジャンルで絞り込み
    if ($request->genre) {
        $query->where('genre', $request->genre);
        // 部分一致なら → $query->where('genre', 'like', '%'.$request->genre.'%');
    }

    // 店名で絞り込み（部分一致）
    if ($request->keyword) {
        $query->where('name', 'like', '%'.$request->keyword.'%');
    }

    $shops = $query->get();

    // region と genre の一覧は DB からユニーク値を取得
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

        $pendingReview = Reservation::where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->where('status', 1) // 1: 利用済み
            ->where('rating', 0) // review カラムが null の場合
            ->first();

        return view('shop.show', compact('shop', 'pendingReview'));
    }
}

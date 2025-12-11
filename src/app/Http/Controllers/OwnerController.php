<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Reservation;
use App\Http\Requests\ShopRequest;

class OwnerController extends Controller
{
    /**
     * 店舗情報の更新画面または登録画面への振り分け
     */
    public function showInfo()
    {
        $shop = auth()->user()->shop;

        if ($shop) {
            return redirect()->route('owner.shop.edit', $shop->id);
        } else {
            return redirect()->route('owner.shop.create');
        }
    }


    /**
     * 店舗情報の登録画面の表示
     */
    public function create()
    {
        return view('owner.shop-info', [
            'shop' => null,
            'isEdit' => false,
        ]);
    }


    /**
     * 店舗情報の更新画面の表示
     */
    public function edit(Shop $shop)
    {
        return view('owner.shop-info', [
            'shop' => $shop,
            'isEdit' => true,
        ]);
    }


    /**
     * 店舗情報の登録処理
     */
    public function store(ShopRequest $request)
    {
        $data = $request->validated();

        $shop = new Shop();
        $shop->user_id = auth()->id();
        $shop->name = $data['name'];
        $shop->region = $data['region'];
        $shop->genre = $data['genre'];
        $shop->description = $data['description'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public');
            $shop->image = basename($path);
        }

        $shop->save();

        return redirect()->route('owner.shop.edit', $shop->id)->with('success', '店舗情報を登録しました');
    }


    /**
     * 店舗情報の更新処理
     */
    public function update(ShopRequest $request, Shop $shop)
    {
        if ($shop->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $data = $request->validated();

        $shop->name = $data['name'];
        $shop->region = $data['region'];
        $shop->genre = $data['genre'];
        $shop->description = $data['description'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public');
            $shop->image = basename($path);
        }

        $shop->save();

        return redirect()->route('owner.shop.edit', $shop->id)->with('success', '店舗情報を更新しました');
    }


    /**
     * 店舗予約情報一覧の表示
     */
    public function index()
    {
        $shop = auth()->user()->shop;
        $reservations = $shop
            ? Reservation::where('shop_id', $shop->id)
                ->where('status', 0)
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->get()
            : collect();

        return view('owner.reservation-list', compact('shop', 'reservations'));
    }


    /**
     * 店舗予約の来店済み処理
     */
    public function complete($reservation)
    {
        $reservation = Reservation::findOrFail($reservation);

        if ($reservation->shop->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $reservation->status = 1;  // 来店済み
        $reservation->save();

        return response()->json(['success' => true]);
    }
}

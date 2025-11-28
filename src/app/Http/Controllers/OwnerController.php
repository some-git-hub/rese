<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Http\Requests\ShopRequest;

class OwnerController extends Controller
{
    public function showInfo()
    {
        $shop = auth()->user()->shop;

        if ($shop) {
            return redirect()->route('owner.shop.edit', $shop->id);
        } else {
            return redirect()->route('owner.shop.create');
        }
    }

    public function create()
    {
        return view('owner.shop-info', [
            'shop' => null,
            'isEdit' => false,
        ]);
    }

    public function edit(Shop $shop)
    {
        return view('owner.shop-info', [
            'shop' => $shop,
            'isEdit' => true,
        ]);
    }

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

        return redirect()->route('owner.shop.info')->with('success', '店舗情報を登録しました。');
    }

    public function update(ShopRequest $request, Shop $shop)
    {
        // ログインユーザーの店舗か確認（セキュリティ）
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

        return redirect()->route('owner.shop.info')->with('success', '店舗情報を更新しました。');
    }

    public function index()
    {
        $shop = auth()->user()->shop;
        $reservations = $shop
            ? Reservation::where('shop_id', $shop->id)->where('status', 0)->get()
            : collect();

        return view('owner.reservation-list', compact('shop', 'reservations'));
    }
}

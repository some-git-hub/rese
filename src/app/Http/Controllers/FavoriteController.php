<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class FavoriteController extends Controller
{
    /**
     * 飲食店のお気に入り登録処理および解除処理
     */
    public function toggle(Shop $shop)
    {
        $user = auth()->user();

        if ($user->favorites()->where('shop_id', $shop->id)->exists()) {
            $user->favorites()->detach($shop->id);

            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->attach($shop->id);

            return response()->json(['status' => 'added']);
        }
    }
}

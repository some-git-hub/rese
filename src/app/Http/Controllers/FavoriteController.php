<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class FavoriteController extends Controller
{
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

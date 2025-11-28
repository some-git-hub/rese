<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    /**
     * ログイン画面の表示(一般ユーザー)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }


    /**
     * ログイン機能(一般ユーザー)
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->role == 2) {
                // 管理者
                return redirect()->route('admin.owner.create');
            } elseif (auth()->user()->role == 1) {
                // 店舗代表者
                return redirect()->route('owner.shop.info');
            } elseif (auth()->user()->role == 0) {
                // ユーザー
                return redirect()->intended(route('shop.list'));
            }
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}

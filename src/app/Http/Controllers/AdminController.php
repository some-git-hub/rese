<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;

class AdminController extends Controller
{
    /**
     * 店舗代表者の作成画面の表示
     */
    public function create()
    {
        return view('admin.create');
    }


    /**
     * 店舗代表者の作成処理
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'],),
            'role' => 1,
        ]);

        return redirect()->route('admin.owner.create')->with('success', '店舗代表者を作成しました');
    }


    /**
     * 店舗代表者一覧の表示
     */
    public function index()
    {
        $owners = User::where('role', 1)->get();

        return view('admin.owner-list', compact('owners'));
    }


    /**
     * 店舗代表者の削除処理
     */
    public function destroy(User $owner)
    {
        $owner->delete();

        return redirect()->route('admin.owner.list')->with('success', '店舗代表者を削除しました');
    }

}

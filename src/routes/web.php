<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// ---- 飲食店一覧 ----
Route::get('/', [ShopController::class, 'index'])->name('shop.list');

// ---- 飲食店詳細 ----
Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shop.show');


// ゲストのみアクセス可能
Route::middleware('guest')->group(function () {

    // ---- 会員登録 ----
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // ---- ログイン ----
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

});


// ---- メール認証誘導 ----
Route::get('/email/verify', [VerifyController::class, 'verifyNotice'])
    ->middleware('auth')
    ->name('verification.notice');

// ---- メール認証リンク ----
Route::get('/email/verify/{id}/{hash}', [VerifyController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// ---- 認証メール再送信 ----
Route::post('/email/verification-notification', [VerifyController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// ---- 会員登録完了 ----
Route::get('/thanks', [RegisterController::class, 'thanks'])
    ->middleware('auth')
    ->name('register.thanks');



// 一般ユーザーのみアクセス可能
Route::middleware('auth', 'user')->group(function () {

    // ---- お気に入り登録/解除 ----
    Route::post('/favorites/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // ---- 予約完了 ----
    Route::post('/done', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/done', [ReservationController::class, 'complete'])->name('reservation.complete');

    // ---- 予約内容の変更 ----
    Route::patch('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');

    // ---- 予約キャンセル ----
    Route::post('/cancel/{reservation}', [ReservationController::class, 'cancel'])->name('reservation.cancel');

    // ---- レビュー ----
    Route::post('/reviews/{reservation}', [ReservationController::class, 'storeReview'])->name('review.store');
    Route::post('/review/{reservation}/skip', [ReservationController::class, 'skip'])->name('review.skip');

    // ---- マイページ ----
    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage.show');

});



// 店舗代表者のみアクセス可能
Route::middleware(['auth', 'owner'])->group(function () {

    // ---- 店舗情報の登録 or 変更 ----
    Route::get('/owner/shop/info', [OwnerController::class, 'showInfo'])->name('owner.shop.info');
    Route::get('/owner/shop/create', [OwnerController::class, 'create'])->name('owner.shop.create');
    Route::get('/owner/shop/edit/{shop}', [OwnerController::class, 'edit'])->name('owner.shop.edit');
    Route::post('/owner/shop/store', [OwnerController::class, 'store'])->name('owner.shop.store');
    Route::put('/owner/shop/update/{shop}', [OwnerController::class, 'update'])->name('owner.shop.update');

    // ---- 予約情報一覧 ----
    Route::get('/owner/reservation/list', [OwnerController::class, 'index'])->name('owner.reservation.list');

    // ---- 予約利用完了 ----
    Route::post('/owner/reservation/{reservation}/complete', [OwnerController::class, 'complete'])->name('owner.reservation.complete');

});



// 管理者のみアクセス可能
Route::middleware(['auth', 'admin'])->group(function () {

    // ---- 店舗代表者の作成 ----
    Route::get('/admin/owner/create', [AdminController::class, 'create'])->name('admin.owner.create');
    Route::post('/admin/owner/store', [AdminController::class, 'store'])->name('admin.owner.store');

    // ---- 店舗代表者一覧 ----
    Route::get('/admin/owner/list', [AdminController::class, 'index'])->name('admin.owner.list');

    // ---- 店舗代表者の削除 ----
    Route::delete('/admin/owner/{owner}', [AdminController::class, 'destroy'])->name('admin.owner.destroy');

});



// ---- ログアウト ----
Route::post('/logout', function () {

    auth()->logout();
    return redirect('/');

})->middleware('auth')->name('logout');
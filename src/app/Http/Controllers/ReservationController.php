<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    /**
     * 予約処理
     */
    public function store(ReservationRequest $request)
    {
        $data = $request->validated();

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $data['shop_id'],
            'date' => $data['date'],
            'time' => $data['time'],
            'people' => $data['people'],
        ]);

        return redirect()->route('reservation.complete');
    }


    /**
     * 予約完了画面の表示
     */
    public function complete()
    {
        return view('reservation.complete');
    }


    /**
     * 予約キャンセル処理
     */
    public function cancel($reservation)
    {
        $reservation = Reservation::findOrFail($reservation);

        if ($reservation->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $reservation->status = 2;
        $reservation->canceled_at = now();
        $reservation->save();

        return response()->json(['success' => true]);
    }


    /**
     * 予約内容の更新処理
     */
    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $data = $request->validated();

        $reservation->update([
            'date' => $data['date'],
            'time' => $data['time'],
            'people' => $data['people'],
        ]);

        return response()->json(['success' => true]);
    }


    /**
     * レビュー保存処理
     */
    public function storeReview(ReviewRequest $request, Reservation $reservation)
    {
        $data = $request->validated();

        $reservation->rating = $data['rating'] ?? 0;
        $reservation->comment = $data['comment'] ?? '';
        $reservation->save();

        return redirect()->back()->with('success', 'レビューありがとうございました！');
    }


    /**
     * レビュースキップ処理
     */
    public function skip($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->rating = -1;
        $reservation->comment = null;
        $reservation->save();

        return redirect()->back();
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'people' => 'required|integer|min:1|max:10',
        ];
    }

    public function attributes()
    {
        return [
            'date' => '予約日',
            'time' => '予約時間',
            'people' => '予約人数',
        ];
    }

    public function messages()
    {
        return [
            'date.after_or_equal' => ':attributeには今日以降の日付を指定してください',
        ];
    }


    /**
     * 今日の予約で現在時刻よりも前の時刻を指定した場合にバリデーションメッセージを表示する
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            $time = $this->input('time');

            if (!$date || !$time) return;

            $reservationDateTime = Carbon::parse("$date $time");
            $currentDateTime     = Carbon::now();

            if (Carbon::parse($date)->isToday() && $reservationDateTime->lessThan($currentDateTime)) {
                $validator->errors()->add('time', '今日の予約は現在時刻以降を指定してください');
            }
        });
    }
}

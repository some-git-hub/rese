@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/owner-list.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">

    <!-- 送信成功メッセージ -->
    @if(session('success'))
    <div class="alert__success">
        <div class="alert__inner">
            <p class="alert__message">{{ session('success') }}</p>
            <div class="alert__button-area">
                <button class="alert__button-close" id="alertOkButton">OK</button>
            </div>
        </div>
    </div>
    @endif

    <h1 class="owner-list__heading">店舗代表者一覧</h1>

    <table class="owner-list__container">
        <tr class="owner-list__row-label">
            <th class="owner-list__label owner-list__label-owner">店舗代表者</th>
            <th class="owner-list__label owner-list__label-email">メールアドレス</th>
            <th class="owner-list__label owner-list__label-shop">店舗名</th>
            <th class="owner-list__label owner-list__label-delete">削除</th>
        </tr>
        @forelse($owners as $owner)
        <tr class="owner-list__row-item">
            <td class="owner-list__item owner-list__item-owner">{{ $owner->name }}</td>
            <td class="owner-list__item owner-list__item-email">{{ $owner->email }}</td>
            <td class="owner-list__item owner-list__item-shop">{{ $owner->shop?->name ?? '未作成' }}</td>
            <td class="owner-list__item owner-list__item-delete">
                <form class="owner-list__delete-form" action="{{ route('admin.owner.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button class="owner-list__button-delete">削除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td class="no-owner" colspan="4">店舗代表者が作成されていません</td>
        </tr>
        @endforelse
    </table>

</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.querySelector('.alert__success');
    const alertInner = document.querySelector('.alert__inner');
    const okButton = document.getElementById('alertOkButton');

    if (!alertBox) return;

    // ---- ① OKボタンで閉じる ----
    okButton.addEventListener('click', function () {
        alertBox.style.display = 'none';
    });

    // ---- ② Enterキーで閉じる ----
    document.addEventListener('keydown', (e) => {
        if (e.key === "Enter" || e.key === "Escape" || e.key === " " || e.key === "Space") {
            alertBox.remove();
        }
    });

    // ---- ③ アラート外クリックで閉じる ----
    alertBox.addEventListener('click', function (e) {
        if (!alertInner.contains(e.target)) {
            alertBox.style.display = 'none';
        }
    });
});
</script>
@endsection
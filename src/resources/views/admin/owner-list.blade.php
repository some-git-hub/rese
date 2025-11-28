@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/owner-list.css') }}" />
@endsection

@section('content')
<div class="all__wrapper">
    <h1>店舗代表者一覧</h1>

    <table>
        <tr>
            <th>店舗名</th>
            <th>名前</th>
            <th>メールアドレス</th>
        </tr>
        @foreach($owners as $owner)
            <tr>
                <td>{{ $owner->shop?->name ?? '未作成' }}</td>
                <td>{{ $owner->name }}</td>
                <td>{{ $owner->email }}</td>
            </tr>
        @endforeach
    </table>

</div>
@endsection
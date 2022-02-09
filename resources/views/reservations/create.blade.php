@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    予約

    <form action="{{ route('reservations.to_confirm') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <input id="name" type="text" name="name" value="{{$coach->name}}" readonly required>
        <input id="area" type="text" name="area" value="{{$coach->area}}" readonly required>
        <input id="fee" type="text" name="fee" value="{{$coach->fee}}" readonly required>
        <input id="avalable_datetime" type="text" name="avalable_datetime" value="{{$coach->avalable_datetime}}" readonly required>

        <input type="date" name="date" min={{date('Y-m-d')}} required>
        <input type="time" name="start_time" step="1800" min={{time()}} required>
        <input type="time" name="end_time" step="1800" min={{time()}} required>
        <input type="text" name="num" required>
        <textarea name="content"  cols="30" rows="10" required></textarea>

        <input type="text" name="id" value="{{$coach->id}}" readonly required>
        <input type="submit" value="確認画面へ進む">
    </form>
@endsection
  
@include('layout.footer')
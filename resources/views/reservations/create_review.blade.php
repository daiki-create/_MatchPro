@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    レビュー

    <form action="{{ route('reservations.save_review') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <textarea name="review"  cols="30" rows="10" required></textarea>

        <input type="text" name="reservation_id" value="{{$reservation_id}}" readonly required>
        <input type="submit" value="送信する">
    </form>
@endsection
  
@include('layout.footer')
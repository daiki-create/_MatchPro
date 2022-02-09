@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    確認

    <form action="{{ route('reservations.save') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <input id="name" type="text" name="name" value="{{$post['name']}}" readonly required>
        <input id="area" type="text" name="area" value="{{$post['area']}}" readonly required>
        <input id="fee" type="text" name="fee" value="{{$post['fee']}}" readonly required>
        <input id="avalable_datetime" type="text" name="avalable_datetime" value="{{$post['avalable_datetime']}}" readonly required>

        <input type="date" name="date" value="{{$post['date']}}" readonly required>
        <input type="time" name="start_time" value="{{$post['start_time']}}" readonly required>
        <input type="time" name="end_time" value="{{$post['end_time']}}" readonly required>
        <textarea name="content"  cols="30" rows="10" readonly required>{{$post['content']}}</textarea>

        <input type="text" name="id" value="{{$post['id']}}" readonly required>
        <input type="submit" value="仮予約">
    </form>
@endsection
  
@include('layout.footer')
@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    @foreach ($coach_list as $coach)
        <div>{{ $coach->name }}</div>
        <div>{{ $coach->loginid }}</div>
        <a href="{{ route('coaches.detail', ['id' => $coach->id]) }}">詳細</a>
    @endforeach
@endsection

@include('layout.footer')
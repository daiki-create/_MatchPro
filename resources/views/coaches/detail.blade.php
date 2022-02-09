@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    <div>{{ $coach->name}}</div>
    <div>{{ $coach->area }}</div>
    <div>{{ $coach->fee }}</div>
    <div>{{ $coach->avalable_datetime }}</div>
    <div>{{ $coach->profile }}</div>

    <a href="{{ route('reservations.create', ['id' => $coach->id]) }}">申し込む</a>
@endsection

@include('layout.footer')
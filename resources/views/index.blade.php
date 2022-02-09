@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    <button onclick="location.href='/temp_coaches/create/1'">
        コーチ登録 
    </button>
    <button onclick="location.href='/temp_coaches/create/0'">
        生徒登録 
    </button>
@endsection
  
@include('layout.footer')
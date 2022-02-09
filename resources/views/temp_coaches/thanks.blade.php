@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    <div class="container">
        ご登録申請ありがとうございます。<br>
        ご入力いただいたメールアドレスに送信したメールから本登録手続きを完了させてください。
    </div>
@endsection
  
@include('layout.footer')
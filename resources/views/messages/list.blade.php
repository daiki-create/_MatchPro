@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    チャット一覧

    @if(isset($message_lists))
        @foreach ($message_lists as $message_list)
            <div>{{ $message_list->opponent_name }}</div>
            <div>{{ $message_list->message }}</div>
            @if($coach_flag)
            <a href="{{ route('messages.talk', ['opponent_id' => $message_list->student_id, 'opponent_name' => $message_list->opponent_name]) }}">トークを開く</a>
            @else
            <a href="{{ route('messages.talk', ['opponent_id' => $message_list->coach_id, 'opponent_name' => $message_list->opponent_name]) }}">トークを開く</a>
            @endif
        @endforeach
    @else
    <p>メッセージはありません。</p>
    @endif
@endsection
  
@include('layout.footer')
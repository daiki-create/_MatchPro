@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    チャット
    
    @if(isset($messages))
        @foreach ($messages as $message)

            @if($coach_flag)
                @if($message->sender == 'coach')
                <div>{{ $my_name }}</div>

                @elseif($message->sender == 'student')
                <div>{{ $opponent_name }}</div>

                @elseif($message->sender == 'system')
                <div>RentaCoach(レンタコーチ)</div>
                @endif
            
            @else
                @if($message->sender == 'coach')
                <div>{{ $opponent_name }}</div>

                @elseif($message->sender == 'student')
                <div>{{ $my_name }}</div>

                @elseif($message->sender == 'system')
                <div>RentaCoach(レンタコーチ)</div>
                @endif
            @endif
            
            <div>{{ $message->message }}</div><br>
        @endforeach
    @else
    <p>メッセージはありません。</p>
    @endif
    <form action="{{ url('messages/save') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">
            <input id="loginid" type="text" class="form-control" name="message" required>
        </div>
        <input type="text" name="opponent_id" value="{{$opponent_id}}">
        <input type="text" name="opponent_name" value="{{$opponent_name}}">
        <button type="submit" name="submit" class="btn btn-primary">{{ __('送信') }}</button>
    </form>
@endsection

@include('layout.footer')
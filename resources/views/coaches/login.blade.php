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
        <form action="{{ url('coaches/auth') }}" method="post">
            @csrf {{-- CSRF保護 --}}
            @method('POST') {{-- 疑似フォームメソッド --}}
            <div class="form-group">

                <label for="loginid">{{ __('メールアドレス') }}</label>
                <input id="loginid" type="email" class="form-control" name="loginid" required>

                <label for="passwd">{{ __('パスワード') }}</label>
                <input id="passwd" type="password" class="form-control" name="passwd" required>

            </div>
            <input type="text" name="coach_flag" value="{{$coach_flag}}">
            @if($coach_flag)
            <button type="submit" name="submit" class="btn btn-primary">{{ __('コーチログイン') }}</button>
            @else
            <button type="submit" name="submit" class="btn btn-primary">{{ __('生徒ログイン') }}</button>
            @endif
        </form>
    </div>
@endsection
  
@include('layout.footer')
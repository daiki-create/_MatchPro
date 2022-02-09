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
        <form action="{{ url('coaches/save') }}" method="post">
            @csrf {{-- CSRF保護 --}}
            @method('POST') {{-- 疑似フォームメソッド --}}
            <div class="form-group">
                <label for="loginid">{{ __('メールアドレス') }}</label>
                <input id="loginid" type="email" class="form-control" name="loginid" value="{{$loginid}}" readonly required>

                <label for="name">{{ __('氏名') }}</label>
                <input id="name" type="text" class="form-control" name="name" required>

                <label for="birth">{{ __('生年月日') }}</label>
                <input id="birth" type="date" class="form-control" name="birth" required>

                <label for="passwd">{{ __('仮パスワード') }}</label>
                <input id="passwd" type="password" class="form-control" name="passwd" required>

            </div>
            <input type="text" name="coach_flag" value="{{$coach_flag}}">
            @if($coach_flag)
            <button type="submit" name="submit" class="btn btn-primary">{{ __('コーチ登録') }}</button>
            @else
            <button type="submit" name="submit" class="btn btn-primary">{{ __('生徒登録') }}</button>
            @endif
        </form>
    </div>
@endsection
  
@include('layout.footer')
@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    基本情報の編集

    <form action="{{ url('mypage/update_icon') }}" method="post" enctype="multipart/form-data">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">
            <label for="icon">{{ __('プロフィール画像') }}</label>
            <input id="icon" type="file" class="form-control" name="icon" accept="image/*" required>
        </div>
        <input id="coach_flag" type="text" value="{{$coach_flag}}" name="coach_flag" readonly required>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('更新') }}</button>
    </form>

    @if($coach_flag)
    <form action="{{ url('mypage/update_traininng') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">
            <label for="area">{{ __('練習場所') }}</label>
            <input id="area" type="text" class="form-control" value="{{$login->area}}" name="area" required>

            <label for="fee">{{ __('30分あたりの料金') }}</label>
            <input id="fee" type="text" class="form-control" value="{{$login->fee}}" name="fee" required>

            <label for="avalable_datetime">{{ __('予約の取りやすい日時') }}</label>
            <textarea name="avalable_datetime" id="avalable_datetime" class="form-control" cols="30" rows="10" required>{{$login->avalable_datetime}}</textarea>

            <label for="profile">{{ __('自己紹介・練習内容') }}</label>
            <textarea name="profile" id="profile" class="form-control" cols="30" rows="10" required>{{$login->profile}}</textarea>
        </div>
        <input id="coach_flag" type="text" value="{{$coach_flag}}" name="coach_flag" readonly required>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('更新') }}</button>
    </form>
    @endif

    <form action="{{ url('mypage/update') }}" method="post">
        @csrf {{-- CSRF保護 --}}
        @method('POST') {{-- 疑似フォームメソッド --}}
        <div class="form-group">
            <label for="loginid">{{ __('メールアドレス') }}</label>
            <input id="loginid" type="email" class="form-control" value="{{$login->loginid}}" name="loginid" readonly required>

            <label for="name">{{ __('氏名') }}</label>
            <input id="name" type="text" class="form-control" value="{{$login->name}}" name="name" required>

            <label for="birth">{{ __('生年月日') }}</label>
            <input id="birth" type="date" class="form-control" value="{{$login->birth}}" name="birth" required>

            <label for="passwd">{{ __('パスワード') }}</label>
            <input id="passwd" type="password" class="form-control" name="passwd" required>

            <label for="re_passwd">{{ __('パスワード(確認)') }}</label>
            <input id="re_passwd" type="password" class="form-control" name="re_passwd" required>
        </div>
        <input id="coach_flag" type="text" value="{{$coach_flag}}" name="coach_flag" readonly required>
        <button type="submit" name="submit" class="btn btn-primary">{{ __('更新') }}</button>
    </form>
@endsection
  
@include('layout.footer')
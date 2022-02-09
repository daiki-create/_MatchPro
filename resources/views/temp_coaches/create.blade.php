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
        <form action="{{ url('temp_coaches/save') }}" method="post">
            @csrf {{-- CSRF保護 --}}
            @method('POST') {{-- 疑似フォームメソッド --}}
            <div class="form-group">
                <label for="loginid">{{ __('メールアドレス') }}</label>
                <input id="loginid" type="email" class="form-control" name="loginid" required>
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
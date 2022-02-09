@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    マイページ


    <!-- <a href="{{ route('cards.charge') }}">課金</a> -->

    @if($login['icon'] != 'default_icon')
    <img src="/storage/icon/{{$login['icon']}}" alt="プロフィール画像">
    @else
    <img src="/icon/default_icon.jpeg" alt="プロフィール画像">
    @endif

    {{$login['loginid']}}
    {{$login['name']}}
    {{$login['status']}}
    
    @if($coach_flag)
        @if($login['identified_status'] == 'unidentified')
        <p>ステータス：非公開（本人確認書類が登録されていません。）</p>

        @elseif($login['identified_status'] == 'checking')
        <p>ステータス：非公開（本人確認書類を確認中です。練習内容なども審査対象のため、未入力の場合は「基本情報の変更」よりご登録ください。）</p>

        @elseif($login['identified_status'] == 'identified')
        <p>ステータス：公開中</p>
        @endif
    @else
        @if($login['identified_status'] == 'unidentified')
        <p>練習を申し込むには本人確認書類の登録が必要です。</p>
        
        @elseif($login['identified_status'] == 'checking')
        <p>ただいま本人確認書類を確認中です。お支払い情報の登録がまだの方は「お支払い情報の登録・変更」よりご登録ください。</p>
        @endif
    @endif

    <a href="/mypage/edit">基本情報の編集</a>
    <a href="/mypage/create_verification_document">本人確認書類登録・更新</a>
    @if($coach_flag)
    <a href="/mypage/create_payroll_account">給与振込口座登録・更新</a>
    @else
    <a href="/cards/create">お支払い情報登録・更新</a>
    @endif
    <br>
    <br>
    <a href="/coaches/logout">ログアウト</a>
    <a href="/coaches/left">退会</a>
    <br>
    <br>
    <a href="/reservations/list">予約一覧</a>
@endsection
  
@include('layout.footer')
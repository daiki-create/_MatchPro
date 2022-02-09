@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    カード登録

    @if(!isset($login['payjp_customer_id']) && $login['status'] == 'active')
    <form action="{{ route('cards.register') }}" method="post">
    @csrf
    <script
        src="https://checkout.pay.jp/"
        class="payjp-button"
        data-key="{{ config('rentacoach.payjp.public_key') }}"
        data-text="カード情報を入力"
        data-submit-text="カードを登録する"
    ></script>
    </form>

    @elseif(isset($login['payjp_customer_id']) && $login['status'] == 'active')
    <form action="{{ route('cards.update') }}" method="post">
    @csrf
    <script
        src="https://checkout.pay.jp/"
        class="payjp-button"
        data-key="{{ config('rentacoach.payjp.public_key') }}"
        data-text="カード情報を入力"
        data-submit-text="カードを変更する"
    ></script>
    </form>
    @elseif(isset($login['payjp_customer_id']) && $login['status'] == 'inactive')
    <form action="{{ route('cards.re_register') }}" method="post">
    @csrf
    <script
        src="https://checkout.pay.jp/"
        class="payjp-button"
        data-key="{{ config('rentacoach.payjp.public_key') }}"
        data-text="カード情報を入力"
        data-submit-text="カードを再登録する"
    ></script>
    </form>
    @endif
    4242 4242 4242 4242
@endsection
  
@include('layout.footer')
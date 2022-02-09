@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の練習の料金につきまして、<br>
ご登録いただいているクレジットカードへ請求させていただきましたが、<br>
なんらかの理由でお支払いが完了となりませんでした。<br>
<br>
ご登録いただいているクレジットカードの情報を更新するか、クレジットカード発行会社に連絡して問題を解決し、お支払いを完了させてください。<br>
<br>
また、行き違いでお支払いが完了している場合は誠に申し訳ございません。<br>
<br>
クレジットカード情報の再登録は以下のリンクよりお手続きをお願いいたします。<br>
{{config('app.url')}}/cards/create<br>
<br>
【練習内容】<br>
氏名：　　　{{$coach['name']}}様<br>
日付：　　　{{$reservation['date']}}<br>
ご利用時間：{{$reservation['start_time']}} ~ {{$reservation['end_time']}}<br>
人数：　　　{{$reservation['num']}}人<br>
合計料金：　{{$reservation['amount']}}円<br>
<br>

@endsection
  
@include('mail.layout.footer')


@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の練習の料金につきまして、お支払いが完了しました。<br>
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
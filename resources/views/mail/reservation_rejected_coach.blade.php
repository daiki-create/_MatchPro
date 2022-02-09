@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の内容の仮予約を出勤不可にしました。<br>
<br>
【お申込み内容】<br>
氏名：　　　{{$student['name']}}様<br>
日付：　　　{{$reservation['date']}}<br>
ご利用時間：{{$reservation['start_time']}} ~ {{$reservation['end_time']}}<br>
人数：　　　{{$reservation['num']}}人<br>
<br>

@endsection
  
@include('mail.layout.footer')


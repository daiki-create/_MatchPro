@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の内容の予約が出勤不可になりました。<br>
<br>
【お申込み内容】<br>
氏名：　　　{{$coach['name']}}様<br>
日付：　　　{{$reservation['date']}}<br>
ご利用時間：{{$reservation['start_time']}} ~ {{$reservation['end_time']}}<br>
人数：　　　{{$reservation['num']}}人<br>
<br>
またのご利用をお待ちしております。<br>
<br>

@endsection
  
@include('mail.layout.footer')


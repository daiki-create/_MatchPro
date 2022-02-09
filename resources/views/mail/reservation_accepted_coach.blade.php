@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の仮予約を承認しました。<br>
<br>
【お申込み内容】<br>
氏名：　　　{{$student['name']}}様<br>
日付：　　　{{{$reservation['date']}}}<br>
ご利用時間：{{$reservation['start_time']}} ~ {{$reservation['end_time']}}<br>
人数：　　　{{$reservation['num']}}人<br>
<br>
<br>
キャンセルをご希望する場合はログイン後に<br>
「マイページ」→「予約一覧」より<br>
「キャンセル」を選択してください。<br>
{{config('app.url')}}/reservations/list<br>
<br>
＊ただし日付によってはペナルティが付与される場合がございますので<br>
あらかじめご了承ください。<br>
<br>

@endsection
  
@include('mail.layout.footer')


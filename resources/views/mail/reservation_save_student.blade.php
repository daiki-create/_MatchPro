@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の内容で仮予約のお申込みをしました。<br>
出勤可否のご連絡をお待ちください。<br>
<br>
【お申込み内容】<br>
氏名：　　　{{$coach['name']}}様<br>
日付：　　　{{$post_data['date']}}<br>
ご利用時間：{{$post_data['start_time']}} ~ {{$post_data['end_time']}}<br>
人数：　　　{{$post_data['num']}}人<br>
<br>
<br>
キャンセルをご希望する場合はログイン後に<br>
「マイページ」→「予約一覧」より<br>
「キャンセル」をクリックしてください。<br>
{{config('app.url')}}/reservations/list<br>
<br>
＊ただし、出勤が確定された後は<br>
日付によってはキャンセル料が発生することがございますので<br>
あらかじめご了承ください。<br>
<br>

@endsection
  
@include('mail.layout.footer')


@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

以下の内容で仮予約のお申込みを受け付けました。<br>
<br>
ログイン後に「マイページ」→「予約一覧」より<br>
出勤可能な場合は「承認」を選択して予約を確定、<br>
出勤不可の場合は「お断り」を選択してください。<br>
{{config('app.url')}}/reservations/list<br>
<br>
【お申込み内容】<br>
氏名：　　　{{$student['name']}}様<br>
日付：　　　{{{$post_data['date']}}}<br>
ご利用時間：{{$post_data['start_time']}} ~ {{$post_data['end_time']}}<br>
人数：　　　{{$post_data['num']}}人<br>
<br>

@endsection
  
@include('mail.layout.footer')


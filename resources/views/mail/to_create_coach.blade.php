
@extends('mail.layout.common')
 
@include('mail.layout.name')
@include('mail.layout.greet')

@section('content')

この度は{{config('app.name')}}へのご登録申請ありがとうございます。<br>
以下のURLより、仮パスワードにて本登録が完了します。<br>
<br>
<br>
【仮パスワード】<br>
{{$temp_passwd}}<br>
<br>
<br>
【本登録URL】<br>
{{$url}}
<br>

@endsection
  
@include('mail.layout.footer')
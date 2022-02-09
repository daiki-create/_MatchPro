@extends('layout.common')
 
@section('title', 'RentaCoach')
@section('keywords', 'コーチ,サッカー,副業')
@section('description', 'サッカーコーチ募集中。30分単位で自由に価格設定ができるフリーランスコーチになりませんか？')
@section('pageCss')
<link href="/css/index.css" rel="stylesheet">
@endsection

@include('layout.header')
 
@section('content')
    予約一覧

    @if(isset($reservations))
        @foreach ($reservations as $reservation)
            @if($reservation->answered_flag == 'unanswered' and ($reservation->status == 'temp' or $reservation->status == 'accepted') and $reservation->charged_flag == 0)
                <div>{{ $reservation->date }}</div>
                <div>{{ $reservation->start_time }}</div>
                <div>{{ $reservation->end_time }}</div>
                <div>{{ $reservation->name }}</div>
                <div>{{ $reservation->content }}</div>
                <div>{{ $reservation->fee }}</div>
                <div>{{ $reservation->num }}</div>
                <div>{{ $reservation->answered_flag }}</div>
                <div>{{ $reservation->status }}</div><br>
                @if($coach_flag)
                    @if($reservation->status == 'temp')
                    <a href="{{ route('reservations.accept', ['reservation_id' => $reservation->id]) }}">承認</a>
                    <a href="{{ route('reservations.reject', ['reservation_id' => $reservation->id]) }}">出勤不可</a>
                    @elseif($reservation->status == 'accepted')
                    <a href="{{ route('reservations.coach_cancel', [
                        'reservation_id' => $reservation->id,
                        'date' => $reservation->date, 
                        'start_time' => $reservation->start_time]) }}">キャンセル</a>
                    @endif
                @else
                <a href="{{ route('reservations.cancel', [
                        'reservation_id' => $reservation->id,
                        'date' => $reservation->date, 
                        'start_time' => $reservation->start_time]) }}">キャンセル</a>

                @endif
            @elseif($reservation->answered_flag == 'unanswered' and $reservation->charged_flag == 1 and $reservation->status == 'accepted')
                @if(!$coach_flag)
                <a href="{{ route('reservations.create_review', ['reservation_id' => $reservation->id]) }}">レビューを書く</a>
                @endif
            @endif
        @endforeach
    @else
    <p>予約はありません。</p>
    @endif

@endsection
  
@include('layout.footer')
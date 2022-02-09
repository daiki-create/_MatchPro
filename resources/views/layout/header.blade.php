@section('header')
<header class="header">
    <a href="/">
        <img src="" alt="RentaCoach">
    </a>
    @if(!session('login'))
    <ul>
        <li><a href="/coaches/login/1">コーチログイン</a></li>
        <li><a href="/coaches/login/0">生徒ログイン</a></li>
    </ul>
    @else
    <ul>
        <li><a href="/mypage">マイページ</a></li>
        <li><a href="/messages/list">チャット</a></li>

        @if(!session('coach_flag'))
        <li><a href="/coaches/list">コーチ一覧</a></li>
        @endif
        
    </ul>
    @endif
</header>
@endsection
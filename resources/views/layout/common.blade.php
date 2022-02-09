<DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>@yield('title')｜nodoame.net</title>

<meta name="description" itemprop="description" content="@yield('description')">
<meta name="keywords" itemprop="keywords" content="@yield('keywords')">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="/css/app.css" rel="stylesheet">
@yield('pageCss')
</head>
<body>
 
@yield('header')
 
<div class="contents">
    <!-- コンテンツ -->
    <div class="main">
        @if(count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        @if (session('flash_message'))
            <div class="flash_message">
                {{ session('flash_message') }}
            </div>
        @endif
        
        @yield('content')
    </div>
</div>
 
@yield('footer')
</body>
</html>
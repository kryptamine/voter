<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,400i,700" media="all">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="page">
        <div class="page__header">
            <div class="page__logo">
                <a href="https://www.xiag.ch" target="_blank">
                    <img src="/images/page-logo.png" alt="XIAG AG">
                </a>
            </div>
            <div class="page__task-name">
                Poll Website Task
            </div>
        </div>
        <div class="page__image">
            <div class="page__task-title">
                Poll Website Task
            </div>
        </div>
        <div class="page__content page__content--padding">
            <div class="poll">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

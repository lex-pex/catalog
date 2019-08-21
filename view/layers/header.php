<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Add-on Web-App">
    <title>Magazine_Catalog</title>
    <link href="/img/favicon_jc.png" rel="icon">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container">
        <img src="/img/favicon_jc.png" width="30px"> _
        <a class="navbar-brand" href="/">Magazine_Catalog</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul><!-- Left Side Of Navbar -->
            <ul class="navbar-nav ml-auto"><!-- Right Side -->
                <? if(guest()): ?><!-- Auth Links -->
                <li class="nav-item">
                    <a class="nav-link" href="<? route('login') ?>">Login</a>
                </li>
                <li class="nav-item" style="display: <? echo (route_exists('register') ? 'inline' : 'none') ?>">
                    <a class="nav-link" href="<? route('register') ?>">Register</a>
                </li>
                <? else: ?>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <? echo user()->name ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<? route('cabinet') ?>">Cabinet</a>
                        <? if(admin()):?><a class="dropdown-item" href="<? route('admin') ?>">Admin</a><? endif ?>
                        <a class="dropdown-item" href="<? route('logout') ?>"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="<? route('logout') ?>" method="post" style="display:none;">
                            <input type="hidden" name="csrf_token" value="<? token() ?>"/>
                        </form>
                    </div>
                </li>
                <? endif ?>
            </ul>
        </div>
    </div>
</nav>
<main role="main">
    <div class="welcome-area">
        <div class="container">
            <div class="mc-logo">
                <img src="/img/favicon_jc.png" width="55px">
                <a href="/">Magazine_Catalog</a>
            </div>
            <p style="max-width: 700px;">Despite our dissatisfaction when being bombarded by all the advertisers' information we must admit that they do perform a useful service to society, and advertisements are an essential part of our everyday life</p>
            <? if(auth()): ?>
            <p><a class="btn btn-danger btn-sm" href="/magazine/create" role="button">Add Magazine &raquo;</a></p>
            <? endif ?>
        </div>
    </div>

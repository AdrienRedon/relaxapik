<!DOCTYPE html>
<html>
    <head>
        <title>Relaxapic {$title}</title>
        <link rel="stylesheet" type="text/css" href="{WEBROOT}css/app.css">
        <meta charset="utf-8"/>
    </head>
    <body>
        <header {if $size=="big"}class="header--big"{/if}>
            <div class="header__logo">
                <a href="{WEBROOT}">
                    <img src="{WEBROOT}img/relaxapic_logo.png" alt="Relaxapic logo" height="75" width="75">
                </a>
            </div>
            <h1 class="header__title">
                RELAXAPIC
            </h1>
            <div class="header__login">
                <a id="signin__button" href="#">S'inscrire</a> <a id="login__button" href="#">Se connecter</a>
            </div>
        </header>
        <nav>
            <a href="{WEBROOT}pathologies"> Pathologies </a>
            <a href="{WEBROOT}salons"> Salons </a>
            <a href="{WEBROOT}membres"> Membres </a>
        </nav>

    {include file="templates/popup/login.tpl"}
    {include file="templates/popup/signin.tpl"}

    <div class="overlay hidden"></div>

    {if isset($flash)}
        {$flash->get()}
    {/if}

    


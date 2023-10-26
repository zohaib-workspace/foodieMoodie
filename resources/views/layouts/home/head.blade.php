<!DOCTYPE html>
<html lang="en">

<head>

    <title>FOODIE MOODIE</title>
    <!-- Favicons-->
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="{{ asset(chk_dmn() . 'home_assets/img/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">


    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
        ! function(e, n, t) {
            "use strict";
            var o = "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap"
                , r = "__3perf_googleFonts_c2536";

            function c(e) {
                (n.head || n.body).appendChild(e)
            }

            function a() {
                var e = n.createElement("link");
                e.href = o, e.rel = "stylesheet", c(e)
            }

            function f(e) {
                if (!n.getElementById(r)) {
                    var t = n.createElement("style");
                    t.id = r, c(t)
                }
                n.getElementById(r).innerHTML = e
            }
            e.FontFace && e.FontFace.prototype.hasOwnProperty("display") ? (t[r] && f(t[r]), fetch(o).then(function(e) {
                return e.text()
            }).then(function(e) {
                return e.replace(/@font-face {/g, "@font-face{font-display:swap;")
            }).then(function(e) {
                return t[r] = e
            }).then(f).catch(a)) : a()
        }(window, document, localStorage);

    </script>

    <!-- BASE CSS !-->
    <link href="{{ asset(chk_dmn() . 'home_assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/style.css') }}" rel="stylesheet">

    <!-- SPECIFIC CSS !-->
    <link href="{{ asset(chk_dmn() . 'home_assets/css/home.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/order-sign_up.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/detail-page.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/order-sign_up.css') }}" rel="stylesheet">
    <!--<link href="{{ asset(chk_dmn() . 'css/order-sign_up.css"') }}" rel="stylesheet">-->
    <link href="{{ asset(chk_dmn() . 'home_assets/css/listing.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/detail-page.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/contacts.css') }}" rel="stylesheet">
    <link href="{{ asset(chk_dmn() . 'home_assets/css/help.css') }}" rel="stylesheet">
    @stack('cdns')

    <!-- YOUR CUSTOM CSS -->
    <link href="{{ asset(chk_dmn() . 'home_assets/css/custom.css') }}" rel="stylesheet">



    <style>
        #loading {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            z-index: 100;

        }

    </style>
    <style>
        .text-teal {
            color: #36888A !important;
        }
        .text-orange {
            color: #F97F35 !important;
        }
        .bg-orange {
            background-color: #F97F35 !important;
        }

        .bg-teal {
            background-color: #36888A !important;
        }

        .btn-teal {
            color: #fff !important;
            background-color: #36888A !important;
        }

        .btn-teal:hover {
            color: #fff !important;
            background-color: #36888A !important;
        }
       
        .bg-orange {
            background-color: #F97F35 !important;
        }

        .btn-orange {
            color: #fff !important ;
            background-color: #F97F35 !important;
        }

        .btn-orange:hover {
            color: #fff !important;
            background-color: #F97F35 !important;
        }
        

    </style>

</head>

<body class="" data-spy="scroll" data-bs-target="#secondary_nav" data-offset="75">

    <div id="loading">
        <div id="loader-container" class="loader-container">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

    </div>

    <!-- Your content goes here -->


    <!-- Your content goes here -->

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Foodie Moodie</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset(chk_dmn().'home_assets/img/logo.png')}}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset(chk_dmn().'home_assets/img/apple-touch-icon-57x57-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset(chk_dmn().'home_assets/img/apple-touch-icon-72x72-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset(chk_dmn().'home_assets/img/apple-touch-icon-114x114-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset(chk_dmn().'home_assets/img/apple-touch-icon-144x144-precomposed.png')}}">
    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    {{-- <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" as="fetch" crossorigin="anonymous"> --}}
    <script type="text/javascript">
    // !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    // </script>

    <!-- BASE CSS -->
    <link href="{{ asset(chk_dmn().'home_assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset(chk_dmn().'home_assets/css/style.css')}}" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="{{ asset(chk_dmn().'home_assets/css/order-sign_up.css')}}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{ asset(chk_dmn().'home_assets/css/custom.css')}}" rel="stylesheet">

</head>

<body id="register_bg">
	<style>
        .form-group input[type="file"] {
            padding-top: 8px; /* Adjust as needed */
            /* padding-bottom: 8px; Adjust as needed */
            line-height: normal; /* Reset line-height */
        }
    </style>
<div id="register">
		<aside>
			<figure>
				<a href="{{route('user.home')}}"><img src="{{ asset(chk_dmn().'home_assets/img/logo.png')}}" width="140" height="60" alt=""></a>
			</figure>
			<div class="access_social">
					{{-- <a href="register.html#0" class="social_bt facebook">Register with Facebook</a> --}}
					<a href="#" class="social_bt google">Register with Google</a>
				</div>
            <div class="divider"><span>Or</span></div>
			<form  action="{{ route('user.register-user') }}" method="post" enctype="multipart/form-data">

				@if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif

                @if(Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
                @endif

                @csrf
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Enter First Name" name="f_name" value="{{ old('f_name') }}">
                    <span class="text-danger">@error('f_name') {{ $message }} @enderror</span>
                    <i class="icon_pencil-edit"></i>
                </div>

                <div class="form-group">
                    <input class="form-control " type="text" placeholder="Enter Last Name" name="l_name" value="{{old('l_name')}}">
                    <span class="text-danger">@error('l_name') {{$message}} @enderror</span>
                    <i class="icon_pencil-edit"></i>
                </div>

                <div class="form-group">
                    <input class="form-control " type="text" placeholder="Enter Phone No" name="phone" value="{{old('phone')}}">
                    <span class="text-danger">@error('phone') {{$message}} @enderror</span>
                    <i class="icon_pencil-edit"></i>
                </div>

                <div class="form-group">
                    <input class="form-control" type="email" placeholder="Enter Email Address" name="email" value="{{old('email')}}">
                    <span class="text-danger">@error('email') {{$message}} @enderror</span>
                    <i class="icon_mail_alt"></i>
                </div>

                <div class="form-group">
                    <input class="form-control" type="password" id="password1" placeholder="Enter Password" name="password" value="" style="width: 83%;">
                    <span class="text-danger">@error('password') {{$message}} @enderror</span>
                    <i class="icon_lock_alt"></i>
                </div>

                <div class="form-group">
                    <input class="form-control" type="password" id="password2" class="form-control" name="password_confirmation" placeholder="Enter confirm_Password" required style="width: 83%;">
                    <span class="text-danger">@error('password_confirmation') {{$message}} @enderror</span>
                    <i class="icon_lock_alt"></i>
                </div>

                <div class="form-group">
                    <input class="form-control" type="file" placeholder="Choose Image" name="image" value="{{old('image')}}">
                    <span class="text-danger">@error('image') {{$message}} @enderror</span>
                    <i class="icon_pencil-edit"></i>
                </div>

				<div  class="clearfix"></div>
                {{-- <button class="btn_1 gradient full-width" type="submit" id="submit">Register Now</button> --}}

                <button class="btn_1 gradient full-width" type="submit" id="registerButton">Register Now!</button>


				<div class="text-center mt-2"><small>Already have an acccount? <strong><a href="{{ route('user.login')}}">Sign In</a></strong></small></div>
			</form>
			<div class="copy">&copy; 2023 Foodie Moodie</div>
		</aside>
</div>
	<!-- /login -->

	<!-- COMMON SCRIPTS -->
    <script src="{{ asset(chk_dmn().'home_assets/js/common_scripts.min.js')}}"></script>
    <script src="{{ asset(chk_dmn().'home_assets/js/common_func.js')}}"></script>
    <script src="{{ asset(chk_dmn().'home_assets/validate.js')}}"></script>

	<!-- SPECIFIC SCRIPTS -->
	<script src="{{ asset(chk_dmn().'home_assets/js/pw_strenght.js')}}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var registerButton = document.getElementById("registerButton");
            var form = document.querySelector("form");

            registerButton.addEventListener("click", function() {
                form.submit();
            });
        });
    </script>

</body>
</html>

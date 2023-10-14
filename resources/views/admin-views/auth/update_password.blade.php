

      <!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="Hazir Hoon At Your Service " />
    <meta name="keywords"
        content="Hazir Hoon , Top Buyer's Seller App" />

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
    <meta name="author" content="pixelstrap" />

    <link rel="icon" href="{{ asset('public/media/extra/pichlogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('public/media/extra/pichlogo.png') }}" type="image/x-icon">
    <title>Update Password
        | Hazir Hoon
    </title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com/" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet" />
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}" />
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}" />
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}" />
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}" />
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}" />
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}" />
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen" />
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />
 <style>
  #login{
        color:black;
    }
    #logo{
        height: 65px !important;
        width: auto;

    }
    #error{
        color: red;
    }

 </style>

</head>

<body>

    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <!-- error page start //-->
    <section>

      <div class="container-fluid p-0">
        <div class="row m-0">
          <div class="col-12 p-0">
            <div class="login-card">
              <div class="login-main">
                <form id="regform" action="{{  Route('reset_password')}}" method="POST" class="theme-form login-form">
                 @csrf
                    <h4 class="mb-3">Reset Your Password</h4>


                  <h6>Create Your Password</h6>
                  <div class="form-group">
                    <label>New Password</label>
                    <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                      <input class="form-control" type="password" id="pass" name="password" required="" placeholder="*********">
                      <!--<div class="show-hide"><span class="show"></span></div>-->
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Retype Password</label>
                    <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                      <input class="form-control" type="password" id="confirm" name="confirm_password" required="" placeholder="*********">
                    </div>
                  </div>
                  <span align="center" id="error" ></span>
                  <div class="form-group">
                    
                    <button class="btn btn-primary btn-block" id="submit" type="submit">Done </button>
                  </div>
                  {{-- <p>Already have an password?<a class="ms-2" href="">Sign in</a></p> --}}
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <script>
        (function() {
            "use strict";
            window.addEventListener(
                "load",
                function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName("needs-validation");
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener(
                            "submit",
                            function(event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add("was-validated");
                            },
                            false
                        );
                    });
                },
                false
            );
        })();
    </script>


    <!-- error page end //-->
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Plugins JS start-->
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <!-- Plugin used-->
    {{-- Notifier JS files --}}
    {{-- <script src="{{ asset('assets/js/notify/index.js') }}"></script> --}}
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            $("#submit").click(function(event) {
                $regexname=/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
                // SWITCH THE COMPARISON OPERATOR
                // $('#pass').val().length < 8
                if (  !$("#pass").val().match($regexname)) {
                    $('#error').html('Password Must have a minimum of 8 characters! It Must Contains One Number and One Letter');
                    event.preventDefault();
                    return false;
                }
                if ($('#pass').val()  !=  $('#confirm').val() ) {
                    $('#error').html('Password And Confirm Password Not Matched!');
                    event.preventDefault();
                    return false;
                }

            })
        });
    </script>



    @if (session()->has('error'))
        <script>
            var data = "<?php echo session('error'); ?>";
            'use strict';
            var notify = $.notify(
                '<i class="fa fa-bell-o text-danger"></i><strong class="text-danger">Status</strong> <span class="text-danger">' +
                data + '</span>', {
                    type: 'theme',
                    allow_dismiss: true,
                    delay: 3000,
                    showProgressbar: true,
                    timer: 300
                });
        </script>
    @endif
</body>
@section('script')
@if (Session::has('success'))
<script>
    notifier_success("{{ Session::get('success') }}");
</script>

@endif
@endsection
<!-- Mirrored from laravel.pixelstrap.com/viho/login-bs-validation by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Jun 2022 11:17:44 GMT -->




</html>

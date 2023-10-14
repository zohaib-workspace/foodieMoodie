<footer>
    <div class="wave footer"></div>
    <div class="container margin_60_40 fix_mobile">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_1">Quick Links</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_1">
                    <ul>
                        <li><a href="{{ route('user.home') }}#">Home</a></li>
                        <li><a href="{{ route('user.home.running_orders') }}">Orders</a></li>
                        <li><a href="{{ route('user.home.restaurants') }}">Restaurants</a></li>
                        <li><a href="{{ route('user.contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('user.help') }}">Help &amp; Faq</a></li>
                        @guest
                            <li><a href="{{ route('user.login') }}">Sign In</a></li>
                            <li><a href="{{ route('user.register') }}">Sign Up</a></li>
                        @endguest
                        
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_2">Restaurants</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_2">
                    <ul>
                        @php
                            $restaurants=App\Models\Restaurant::take(3)->get();
                        @endphp
                        @foreach ($restaurants as $r)
                        <li><a href="{{route('user.restaurent_details', $r->id)}}">{{$r->name}}</a></li>
                            
                        @endforeach
                        {{-- <li><a href="#">Best Rated</a></li>
                        <li><a href="#">Best Price</a></li>
                        <li><a href="#">Latest Submissions</a></li> --}}
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_3">Contacts</h3>
                <div class="collapse dont-collapse-sm contacts" id="collapse_3">
                    <ul>
                        <li><i class="icon_house_alt"></i>97845 Baker st. 567<br>Los Angeles - US</li>
                        <li><i class="icon_mobile"></i>+94 423-23-221</li>
                        <li><i class="icon_mail_alt"></i><a href="#">info@domain.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_4">Keep in touch</h3>
                <div class="collapse dont-collapse-sm" id="collapse_4">
                    <div id="newsletter">
                        <div id="message-newsletter"></div>
                        <form name="newsletter_form" id="newsletter_form">
                            <div class="form-group">
                                <input type="email" name="email_newsletter" id="email_newsletter" class="form-control"
                                    placeholder="Your email">
                                <button type="submit" id="submit-newsletter"><i
                                        class="arrow_carrot-right"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="follow_us">
                        <h5>Follow Us</h5>
                        <ul>
                            <li><a href="#"><img
                                        src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                        data-src="{{ asset('public/home_assets/img/twitter_icon.svg') }}" alt=""
                                        class="lazy"></a></li>
                            <li><a href="#"><img
                                        src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                        data-src="{{ asset('public/home_assets/img/facebook_icon.svg') }}"
                                        alt="" class="lazy"></a></li>
                            <li><a href="#"><img
                                        src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                        data-src="{{ asset('public/home_assets/img/instagram_icon.svg') }}"
                                        alt="" class="lazy"></a></li>
                            <li><a href="#"><img
                                        src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                                        data-src="{{ asset('public/home_assets/img/youtube_icon.svg') }}"
                                        alt="" class="lazy"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row-->
        <hr>
        <div class="row add_bottom_25">
            {{-- <div class="col-lg-6">
                <ul class="footer-selector clearfix">
                    <li>
                        <div class="styled-select lang-selector">
                            <select>
                                <option value="English" selected>English</option>
                                <option value="French">French</option>
                                <option value="Spanish">Spanish</option>
                                <option value="Russian">Russian</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="styled-select currency-selector">
                            <select>
                                <option value="US Dollars" selected>US Dollars</option>
                                <option value="Euro">Euro</option>
                            </select>
                        </div>
                    </li>
                    <li><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="
                            data-src="{{ asset('public/home_assets/img/cards_all.svg') }}" alt=""
                            width="230" height="35" class="lazy"></li>
                </ul>
            </div> --}}
            <div class="col-lg-6 mx-auto">
                <ul class="additional_links">
                    <li><a href="{{route('terms-and-conditions')}}">Terms and conditions</a></li>
                    <li><a href="{{route('privacy-policy')}}">Privacy</a></li>
                    <li><span>&copy;2023 Foodie Moodie</span></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div id="toTop"></div><!-- Back to top button -->


<!-- Sign In Modal -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
    <div class="modal_header">
        <h3>Sign In</h3>
    </div>
    <form>
        <div class="sign-in-wrapper">
            <a href="#0" class="social_bt facebook">Login with Facebook</a>
            <a href="#0" class="social_bt google">Login with Google</a>
            <div class="divider"><span>Or</span></div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="email">
                <i class="icon_mail_alt"></i>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" id="password" value="">
                <i class="icon_lock_alt"></i>
            </div>
            <div class="clearfix add_bottom_15">
                <div class="checkboxes float-start">
                    <label class="container_check">Remember me
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="float-end"><a id="forgot" href="javascript:void(0);">Forgot Password?</a></div>
            </div>
            <div class="text-center">
                <input type="submit" value="Log In" class="btn_1 full-width mb_5">
                Don’t have an account? <a href="register.html">Sign up</a>
            </div>
            <div id="forgot_pw">
                <div class="form-group">
                    <label>Please confirm login email below</label>
                    <input type="email" class="form-control" name="email_forgot" id="email_forgot">
                    <i class="icon_mail_alt"></i>
                </div>
                <p>You will receive an email containing a link allowing you to reset your password to a new preferred
                    one.</p>
                <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
            </div>
        </div>
    </form>
    <!--form -->
</div>
<!-- /Sign In Modal -->

<!-- COMMON SCRIPTS -->

</body>

</html>

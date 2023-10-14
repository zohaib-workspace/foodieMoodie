<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Api\V1\OrderController as ApiOrderController;
use App\Http\Controllers\Auth\LoginController;
//user controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController as RestaurantControllerInHttp;
use App\Http\Controllers\UserRestaurentController;
use App\Http\Controllers\Vendor\AddOnController;
use App\Http\Controllers\Vendor\FoodController;
use App\Http\Controllers\Vendor\RestaurantController;
use App\Http\Controllers\Vendor\ReviewController;
use App\Http\Middleware\AlreadyLoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Jazz Cash Payment Integration*/

//Route::get('/admin/jazz',[JazzcashController::class] , 'jazz_page');

Route::get('/admin/jazzcash', 'JazzcashController@do_checkout');
Route::post('/admin/payment_status', 'JazzcashController@payment_status');

/*Jazz Cash End*/


/*Vendor Web Views*/

//food product routes
Route::get('/add_product_mobile', [FoodController::class, 'add_product']);
Route::get('/edit_product_mobile', [FoodController::class, 'edit_product_mobile']);

Route::get('success', [FoodController::class, 'success'])->name('success');
//food product routes

//deal routes
Route::get('create_deal_mobile', [FoodController::class, 'create_deal_mobile']);
Route::get('get_variants_mobile', [FoodController::class, 'get_variants_mobile']);
Route::post('store_deal_mobile', [FoodController::class, 'store_deal_mobile'])->name('store_deal_mobile');
Route::get('success-mobile', [FoodController::class, 'success'])->name('success-mobile');
Route::get('deal-list-mobile', [FoodController::class, 'deal_list_mobile'])->name('deal-list-mobile');
Route::get('deal-edit-mobile/{id}', [FoodController::class, 'deal_edit_mobile'])->name('deal-edit-mobile');
Route::post('deal-update-mobile/{id}', [FoodController::class, 'deal_update_mobile'])->name('deal-update-mobile');
//end deal routes

//Addon routes
Route::get('addon-new-mobile', [AddOnController::class, 'index_mobile'])->name('addon-new-mobile');
Route::post('store-mobile', [AddOnController::class, 'store_mobile'])->name('store-mobile');
Route::get('edit-addon-mobile/{id}', [AddOnController::class, 'edit_mobile'])->name('edit-mobile');
Route::post('update-mobile/{id}', [AddOnController::class, 'update_mobile'])->name('update-mobile');
//end Addon route

//start Business Info

Route::get('business-view-mobile', [RestaurantController::class, 'business_view_mobile'])->name('business-view-mobile');
Route::get('business-edit-mobile', [RestaurantController::class, 'business_edit_mobile'])->name('business-edit-mobile');
Route::post('business-update-mobile', [RestaurantController::class, 'business_update_mobile'])->name('business-update-mobile');
//end Business Info

//start Reviews
Route::get('reviews-mobile', [ReviewController::class, 'reviews_mobile'])->name('reviews-mobile');
//end reviews
/*Vendor Web Views*/

Route::get('/', 'HomeController@index')->name('home');
// Route::get('/', 'DashboardController@dashboard')->name('dashboard');
Route::get('show_about_responsive', 'HomeController@show_about_responsive')->name('show_about_responsive');
Route::get('show_terms_responsive', 'HomeController@show_terms_responsive')->name('show_terms_responsive');
Route::get('show_privacy_responsive', 'HomeController@show_privacy_responsive')->name('show_privacy_responsive');


Route::get('terms-and-conditions', 'HomeController@terms_and_conditions')->name('terms-and-conditions');
Route::get('about-us', 'HomeController@about_us')->name('about-us');
Route::get('contact-us', 'HomeController@contact_us')->name('contact-us');
Route::get('privacy-policy', 'HomeController@privacy_policy')->name('privacy-policy');
Route::post('newsletter/subscribe', 'NewsletterController@newsLetterSubscribe')->name('newsletter.subscribe');
Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthenticated.']);
    return response()->json([
        'errors' => $errors,
    ], 401);
})->name('authentication-failed');

Route::group(['prefix' => 'payment-mobile'], function () {
    Route::get('/', 'PaymentController@payment')->name('payment-mobile');
    Route::get('set-payment-method/{name}', 'PaymentController@set_payment_method')->name('set-payment-method');
});

// SSLCOMMERZ Start

Route::post('pay-ssl', 'SslCommerzPaymentController@index')->name('pay-ssl');
Route::post('/success', 'SslCommerzPaymentController@success');
Route::post('/fail', 'SslCommerzPaymentController@fail');
Route::post('/cancel', 'SslCommerzPaymentController@cancel');
Route::post('/ipn', 'SslCommerzPaymentController@ipn');
//SSLCOMMERZ END

/*paypal*/
/*Route::get('/paypal', function (){return view('paypal-test');})->name('paypal');*/
Route::post('pay-paypal', 'PaypalPaymentController@payWithpaypal')->name('pay-paypal');
Route::get('paypal-status', 'PaypalPaymentController@getPaymentStatus')->name('paypal-status');
/*paypal*/


Route::get('pay-stripe', 'StripePaymentController@payment_process_3d')->name('pay-stripe');
Route::get('pay-stripe/success/{order_id}/{transaction_ref}', 'StripePaymentController@success')->name('pay-stripe.success');
Route::get('pay-stripe/fail', 'StripePaymentController@fail')->name('pay-stripe.fail');

// Get Route For Show Payment Form
Route::get('paywithrazorpay', 'RazorPayController@payWithRazorpay')->name('paywithrazorpay');
Route::post('payment-razor/{order_id}', 'RazorPayController@payment')->name('payment-razor');

/*Route::fallback(function () {
return redirect('/admin/auth/login');
});*/


Route::get('payment-success', 'PaymentController@success')->name('payment-success');
Route::get('payment-fail', 'PaymentController@fail')->name('payment-fail');

//senang pay
Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');

// paymob
Route::post('/paymob-credit', 'PaymobController@credit')->name('paymob-credit');
Route::get('/paymob-callback', 'PaymobController@callback')->name('paymob-callback');

//paystack
Route::post('/paystack-pay', 'PaystackController@redirectToGateway')->name('paystack-pay');
Route::get('/paystack-callback', 'PaystackController@handleGatewayCallback')->name('paystack-callback');
Route::get('/paystack', function () {
    return view('paystack');
});


// The route that the button calls to initialize payment
Route::post('/flutterwave-pay', 'FlutterwaveController@initialize')->name('flutterwave_pay');
// The callback url after a payment
Route::get('/rave/callback/{order_id}', 'FlutterwaveController@callback')->name('flutterwave_callback');


// The callback url after a payment
Route::get('mercadopago/home', 'MercadoPagoController@index')->name('mercadopago.index');
Route::post('mercadopago/make-payment', 'MercadoPagoController@make_payment')->name('mercadopago.make_payment');
Route::get('mercadopago/get-user', 'MercadoPagoController@get_test_user')->name('mercadopago.get-user');

//paytabs
Route::any('/paytabs-payment', 'PaytabsController@payment')->name('paytabs-payment');
Route::any('/paytabs-response', 'PaytabsController@callback_response')->name('paytabs-response');

//bkash
Route::group(['prefix' => 'bkash'], function () {
    // Payment Routes for bKash
    Route::post('get-token', 'BkashPaymentController@getToken')->name('bkash-get-token');
    Route::post('create-payment', 'BkashPaymentController@createPayment')->name('bkash-create-payment');
    Route::post('execute-payment', 'BkashPaymentController@executePayment')->name('bkash-execute-payment');
    Route::get('query-payment', 'BkashPaymentController@queryPayment')->name('bkash-query-payment');
    Route::post('success', 'BkashPaymentController@bkashSuccess')->name('bkash-success');
});

// The callback url after a payment PAYTM
Route::get('paytm-payment', 'PaytmController@payment')->name('paytm-payment');
Route::any('paytm-response', 'PaytmController@callback')->name('paytm-response');

// The callback url after a payment LIQPAY
Route::get('liqpay-payment', 'LiqPayController@payment')->name('liqpay-payment');
Route::any('liqpay-callback/{order_id}', 'LiqPayController@callback')->name('liqpay-callback');

Route::get('wallet-payment', 'WalletPaymentController@make_payment')->name('wallet.payment');

Route::get('/test', function () {
    return view('errors.404');
    dd('Hello tester');
});

Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
    return response()->json([
        'errors' => $errors
    ], 401);
})->name('authentication-failed');

Route::get('module-test', function () {
});

//Restaurant Registration
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('a', function () {
        echo 'here';
    });
});
Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.'], function () {
    Route::get('apply', 'VendorController@create')->name('create');
    Route::post('apply', 'VendorController@store')->name('store');
});

//Deliveryman Registration
Route::group(['prefix' => 'deliveryman', 'as' => 'deliveryman.'], function () {
    Route::get('apply', 'DeliveryManController@create')->name('create');
    Route::post('apply', 'DeliveryManController@store')->name('store');
});

//users routes

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/', [HomeController::class, 'user'])->name('home');

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register-user', [RegisterController::class, 'user_register'])->name('register-user');
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('/login-user', [LoginController::class, 'user_login'])->name('user_login');


    Route::middleware([alreadyLoggedIn::class])->group(function () {
        // Route::middleware([isloggedIn::class])->group(function () {

        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        // Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('contact', [HomeController::class, 'contact'])->name('contact');
        Route::get('order', [OrderController::class, 'index'])->name('order');
        Route::get('running-orders', [OrderController::class, 'running_orders'])->name('home.running_orders');
        Route::get('order-detail', [OrderController::class, 'order_detail'])->name('home.running_orders');
        Route::get('confirm-order', [OrderController::class, 'confirm_order'])->name('confirm-order');
        Route::get('blog', [BlogController::class, 'blog'])->name('blog');
        Route::get('help', [HomeController::class, 'help'])->name('help');
        Route::get('list-map', [RestaurantControllerInHttp::class, 'list_map'])->name('list-map');
        Route::get('submit-raustaurent', [UserRestaurentController::class, 'submit_raustaurent'])->name('submit-raustaurent');
        Route::get('detail-raustaurent', [UserRestaurentController::class, 'detail_raustaurent'])->name('detail-raustaurent');
        Route::get('restaurants', [UserRestaurentController::class, 'index'])->name('home.restaurants');
        Route::get('restaurent_details/{id}', [UserRestaurentController::class, 'restaurent_details'])->name('restaurent_details');
        // Route::group(['middleware'=>'auth'],function(){

        // });
    });
});
//



// Duplicate routes of apis here just for to fetch Auth 
Route::group(['middleware' => 'auth'], function () {
    Route::post('order_place_web', [OrderController::class, 'place_order']);
});

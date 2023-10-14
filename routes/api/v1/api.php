<?php

use Illuminate\Support\Facades\Route;
use App\Services\FirebaseService;
use App\Http\Controllers\Api\V1\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\V1', 'middleware'=>'localization'], function () {
    
    //Route::post('update_password',[UserController::class,'update_user_pass']);
    //Route::get('forgot_password/{rand?}',[UserController::class,'update_forgot_pass']);
    //Route::post('reset_password/{id}',[UserController::class, 'reset_user_password']);
    
    Route::get('test','CustomerController@test');
    /*Vendor Web Views*/
    Route::group(['prefix' => 'vendor', 'namespace' => 'Vendor'], function () {
        
        Route::get('deal-list', 'FoodController@deal_list');
        Route::get('deal-delete', 'FoodController@deal_delete');
        Route::get('deal-status', 'FoodController@deal_status');
        Route::post('store_mobile', 'FoodController@store')->name('store_mobile');
        Route::post('update_mobile', 'FoodController@update_mobile')->name('update_mobile');
    });
    /*Vendor Web Veiws End*/
    
    Route::get('privacy-policy',function(){
            return view('privacy-policy');
        });
        Route::get('terms-conditions',function(){
            return view('terms-and-conditions');
        });
    Route::get('zone/list', 'ZoneController@get_zones');
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    
        Route::get('get_data', function(){
            return \App\Services\FirebaseService::setData('121', 333);
        });
        Route::post('sign-up', 'CustomerAuthController@register');
        Route::post('login', 'CustomerAuthController@login');
        Route::post('google_auth', 'CustomerAuthController@google_auth');
        Route::post('verify-phone', 'CustomerAuthController@verify_phone');

        Route::post('check-email', 'CustomerAuthController@check_email');
        Route::post('verify-email', 'CustomerAuthController@verify_email');
        
        
        /*Reset through Email*/
        Route::post('forgot-password-link-request', 'PasswordResetController@forgot_password_link_request');
        
        /*Rest throught Phone*/
        Route::post('forgot-password', 'PasswordResetController@reset_password_request');
        Route::post('verify-token', 'PasswordResetController@verify_token');
        Route::put('reset-password', 'PasswordResetController@reset_password_submit');

        Route::group(['prefix' => 'delivery-man'], function () {
            Route::post('login', 'DeliveryManLoginController@login');
            Route::post('store', 'DeliveryManLoginController@store');
            Route::post('forgot-password', 'DMPasswordResetController@reset_password_request');
            Route::post('verify-token', 'DMPasswordResetController@verify_token');
            Route::put('reset-password', 'DMPasswordResetController@reset_password_submit');
            Route::get('get_reviews/{id}', 'DeliveryManLoginController@get_reviews');
        });
        Route::group(['prefix' => 'vendor'], function () {
            Route::post('login', 'VendorLoginController@login');
            Route::post('forgot-password', 'VendorPasswordResetController@reset_password_request');
            Route::post('verify-token', 'VendorPasswordResetController@verify_token');
            Route::put('reset-password', 'VendorPasswordResetController@reset_password_submit');
            Route::post('register','VendorLoginController@register');
        });

        //social login(up comming)
        // Route::post('social-login', 'SocialAuthController@social_login');
        // Route::post('social-register', 'SocialAuthController@social_register');
    });
    
    Route::post('send_chat_noti', 'NotificationController@send_chat_noti');
    
    Route::group(['prefix' => 'delivery-man','as' => 'delivery-man'], function () {
       Route::get('assign_order', 'OrderController@assign_order');
        Route::get('last-location', 'DeliverymanController@get_last_location');
        Route::get('order_details', 'OrderController@get_order_details');
        Route::get('running_order_details', 'OrderController@get_running_order_details');
        Route::get('change_order_status', 'OrderController@change_order_status');
        Route::get('completed-orders/{type}', 'OrderController@get_completed_orders');
        Route::post('order-earning', 'OrderController@handleOrderEarning');
        Route::get('test', 'ShiftController@test');

        Route::group(['prefix' => 'reviews','middleware'=>['auth:api']], function () {
            Route::get('/{delivery_man_id}', 'DeliveryManReviewController@get_reviews');
            Route::get('rating/{delivery_man_id}', 'DeliveryManReviewController@get_rating');
            Route::post('/submit', 'DeliveryManReviewController@submit_review');
        });
        Route::group(['middleware'=>['dm.api']], function () {
            Route::post('shifts', 'ShiftController@get_shifts');
            Route::post('get_ended_shifts', 'ShiftController@get_ended_shifts');
            Route::post('upcoming_shifts', 'ShiftController@get_upcoming_shifts');
            Route::post('take_shift', 'ShiftController@take_shift');
            Route::post('start_shift', 'ShiftController@start_shift');
            Route::post('current_shift', 'ShiftController@get_current_started_shifts');
            Route::get('assign_order', 'OrderController@assign_order');
            Route::get('accept_order', 'OrderController@acceptOrder');
            Route::get('profile', 'DeliverymanController@get_profile');
            Route::get('get_wallet_data', 'DeliverymanController@get_wallet_data');
            Route::get('end_shift', 'ShiftController@end_shift');
            Route::get('pause_shift', 'ShiftController@pause_shift');
            Route::get('resume_shift', 'ShiftController@resume_shift');
            Route::get('notifications', 'NotificationController@get_notifications');
            Route::get('read_notification/{id}', 'NotificationController@read_rider_notification');
            
            Route::post('update-profile', 'DeliverymanController@update_profile');
            Route::post('update-active-status', 'DeliverymanController@activeStatus');
            Route::get('current-orders', 'DeliverymanController@get_current_orders');
            Route::get('latest-orders', 'DeliverymanController@get_latest_orders');
            Route::post('record-location-data', 'DeliverymanController@record_location_data');
            Route::get('all-orders', 'DeliverymanController@get_all_orders');
            Route::get('order-delivery-history', 'DeliverymanController@get_order_history');
            Route::put('accept-order', 'DeliverymanController@accept_order');
            Route::get('update-order-status', 'DeliverymanController@update_order_status');
            Route::put('update-payment-status', 'DeliverymanController@order_payment_status_update');
            Route::get('order-details', 'DeliverymanController@get_order_details');
            Route::get('order', 'DeliverymanController@get_order');
            Route::post('update_location', 'DeliverymanController@update_location');
            Route::post('update-fcm-token', 'DeliverymanController@update_fcm_token');
            
            //Remove account
            Route::delete('remove-account', 'DeliverymanController@remove_account');


            // Chatting
            Route::group(['prefix' => 'message'], function () {
                Route::get('list', 'ConversationController@dm_conversations');
                Route::get('search-list', 'ConversationController@dm_search_conversations');
                Route::get('details', 'ConversationController@dm_messages');
                Route::post('send', 'ConversationController@dm_messages_store');
            });
        });
    });


    Route::group(['prefix' => 'vendor', 'namespace' => 'Vendor', 'middleware'=>['vendor.api']], function () {
        
        Route::get('notifications', 'VendorController@get_notifications');
        Route::get('profile', 'VendorController@get_profile');
        Route::post('update-active-status', 'VendorController@active_status');
        Route::get('earning-info', 'VendorController@get_earning_data');
        Route::put('update-profile', 'VendorController@update_profile');
        Route::get('current-orders', 'VendorController@get_current_orders');
        Route::get('completed-orders', 'VendorController@get_completed_orders');
        Route::get('all-orders', 'VendorController@get_all_orders');
        Route::get('update-order-status', 'VendorController@update_order_status');
        Route::get('order-details', 'VendorController@get_order_details');
        Route::get('order', 'VendorController@get_order');
        Route::post('update-fcm-token', 'VendorController@update_fcm_token');
        Route::get('get-basic-campaigns', 'VendorController@get_basic_campaigns');
        Route::put('campaign-leave', 'VendorController@remove_restaurant');
        Route::put('campaign-join', 'VendorController@addrestaurant');
        Route::get('get-withdraw-list', 'VendorController@withdraw_list');
        Route::get('get-products-list', 'VendorController@get_products');
        Route::get('assign-order/{id}', 'VendorController@assign_order');
        Route::post('update-products-status', 'VendorController@update_products_status');
        Route::put('update-bank-info', 'VendorController@update_bank_info');
        Route::post('request-withdraw', 'VendorController@request_withdraw');
        Route::get('get-wallet-data', 'VendorController@get_wallet_data');
        
        // update/edit product
        

        //remove account
        Route::delete('remove-account', 'VendorController@remove_account');

        // Business setup
        Route::put('update-business-setup', 'BusinessSettingsController@update_restaurant_setup');

        // Reataurant schedule
        Route::post('schedule/store', 'BusinessSettingsController@add_schedule');
        Route::delete('schedule/{restaurant_schedule}', 'BusinessSettingsController@remove_schedule');

        // Attributes
        Route::get('attributes', 'AttributeController@list');

        // Addon
        Route::group(['prefix'=>'addon'], function(){
            Route::get('/', 'AddOnController@list');
            Route::post('store', 'AddOnController@store')->name('product.store');
            Route::put('update', 'AddOnController@update');
            Route::get('status', 'AddOnController@status');
            Route::delete('delete', 'AddOnController@delete');
        });

        Route::group(['prefix' => 'delivery-man'], function () {
            Route::post('store', 'DeliveryManController@store');
            Route::get('list', 'DeliveryManController@list');
            Route::get('preview', 'DeliveryManController@preview');
            Route::get('status', 'DeliveryManController@status');
            Route::post('update/{id}', 'DeliveryManController@update');
            Route::delete('delete', 'DeliveryManController@delete');
            Route::post('search', 'DeliveryManController@search');
        });
        // Food
        Route::group(['prefix'=>'product'], function(){
            Route::get('index', 'FoodController@index');
            Route::post('store', 'FoodController@store');
            Route::put('update', 'FoodController@update');
            Route::get('delete', 'FoodController@delete');
            Route::get('status', 'FoodController@status');
            Route::post('search', 'FoodController@search');
            Route::get('reviews', 'FoodController@reviews');
            Route::post('variant-combination', 'FoodController@variant_combination')->name('combination');

        });

        // POS
        Route::group(['prefix'=>'pos'], function(){
            Route::get('orders', 'POSController@order_list');
            Route::post('place-order', 'POSController@,j');
            Route::get('customers', 'POSController@get_customers');
        });

        // Chatting
        Route::group(['prefix' => 'message'], function () {
            Route::get('list', 'ConversationController@conversations');
            Route::get('search-list', 'ConversationController@search_conversations');
            Route::get('details', 'ConversationController@messages');
            Route::post('send', 'ConversationController@messages_store');
        });
    });


    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
        Route::get('/get-zone-id', 'ConfigController@get_zone');
        Route::get('place-api-autocomplete', 'ConfigController@place_api_autocomplete');
        Route::get('distance-api', 'ConfigController@distance_api');
        Route::get('place-api-details', 'ConfigController@place_api_details');
        Route::get('geocode-api', 'ConfigController@geocode_api');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('latest', 'ProductController@get_latest_products');
        Route::get('popular', 'ProductController@get_popular_products');
        Route::get('most-reviewed', 'ProductController@get_most_reviewed_products');
        Route::get('set-menu', 'ProductController@get_set_menus');
        Route::get('search', 'ProductController@get_searched_products');
        Route::get('details/{id}', 'ProductController@get_product');
        Route::post('sync', 'ProductController@get_products');
        Route::get('related-products/{food_id}', 'ProductController@get_related_products');
        Route::get('reviews/{food_id}', 'ProductController@get_product_reviews');
        Route::get('rating/{food_id}', 'ProductController@get_product_rating');
        Route::post('reviews/submit', 'ProductController@submit_product_review')->middleware('auth:api');
        
        Route::get('deal-detail/{deal_id}', 'ProductController@getDealDetail');
        Route::get('deals/{restaurant_id}', 'ProductController@getDeals');
        Route::get('all_deals', 'ProductController@get_all_deals');
        Route::get('get_top_deals', 'ProductController@get_top_deals');
    });

    Route::group(['prefix' => 'restaurants'], function () {
        Route::get('get-restaurants/{filter_data}', 'RestaurantController@get_restaurants');
        Route::get('latest', 'RestaurantController@get_latest_restaurants');
        Route::get('popular', 'RestaurantController@get_popular_restaurants');
        Route::get('details/{id}', 'RestaurantController@get_details');
        Route::get('get_restaurant_reviews/{id}', 'RestaurantController@get_restaurant_reviews');
        Route::get('reviews', 'RestaurantController@reviews');
        Route::get('search', 'RestaurantController@get_searched_restaurants');
        // Route::get('products', 'RestaurantController@get_products');
    });
    
    Route::group(['prefix' => 'business'], function () {
        Route::get('get_services', 'GeneralController@get_services');
        
    });
        
    
    Route::group(['prefix' => 'express'], function () {
        Route::get('get_express_categories', 'GeneralController@get_express_categories');
        Route::post('place_express_order', 'ExpressController@place_express_order');
        Route::post('find_price', 'ExpressController@calculate_price');
        Route::get('get_absher_express_list/{id}', 'ExpressController@get_absher_express_list');
    });
    

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
        Route::get('business', 'BusinessSliderController@get_banners');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@get_categories');
        Route::get('childes/{category_id}/{store_id}', 'CategoryController@get_childes');
        Route::get('products/{category_id}', 'CategoryController@get_products');
        Route::get('products/{category_id}/all', 'CategoryController@get_all_products');
        Route::get('restaurants/{category_id}', 'CategoryController@get_restaurants');
        Route::post('products', 'CategoryController@get_categories_products');

    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function () {
        Route::get('notifications', 'NotificationController@get_notifications');
        Route::get('read_notification/{id}', 'NotificationController@read_user_notification');
        Route::get('info', 'CustomerController@info');
        Route::get('send_noti/{id}', 'GeneralController@send_noti');
        Route::get('update-zone', 'CustomerController@update_zone');
        Route::post('update-profile', 'CustomerController@update_profile');
        Route::post('update-interest', 'CustomerController@update_interest');
        Route::post('fcm-firebase-token', 'CustomerController@update_cm_firebase_token');
        Route::get('suggested-foods', 'CustomerController@get_suggested_food');
        //Remove account
        Route::delete('remove-account', 'CustomerController@remove_account');
        Route::get('business-request','BusinessRequestController@submitRequest');

        Route::group(['prefix'=>'loyalty-point'], function() {
            Route::post('point-transfer', 'LoyaltyPointController@point_transfer');
            Route::get('transactions', 'LoyaltyPointController@transactions');
            Route::get('loyalty_gifts', 'LoyaltyPointController@loyalty_gifts');
            Route::post('redeem_gift', 'LoyaltyPointController@redeem_gift');
            Route::get('loyalty_requests', 'LoyaltyPointController@loyalty_requests');
        });

        Route::group(['prefix'=>'wallet'], function() {
            Route::get('transactions', 'WalletController@transactions');
        });
        

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'CustomerController@address_list');
            Route::post('add', 'CustomerController@add_new_address');
            Route::put('update/{id}', 'CustomerController@update_address');
            Route::delete('delete', 'CustomerController@delete_address');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list', 'OrderController@get_order_list');
            Route::get('running-orders', 'OrderController@get_running_orders');  
            Route::get('completed-orders', 'OrderController@get_completed_orders');
            Route::get('details', 'OrderController@get_order_details');
            Route::post('place', 'OrderController@place_order');
            Route::post('order_shipping_charges', 'OrderController@order_shipping_charges');
            Route::post('cancel', 'OrderController@cancel_order');
            Route::put('refund-request', 'OrderController@refund_request');
            Route::get('track', 'OrderController@track_order');
            Route::put('payment-method', 'OrderController@update_payment_method');
        });
        // Chatting
        Route::group(['prefix' => 'message'], function () {
            Route::get('list', 'ConversationController@conversations');
            Route::get('search-list', 'ConversationController@get_searched_conversations');
            Route::get('details', 'ConversationController@messages');
            Route::post('send', 'ConversationController@messages_store');
            Route::post('chat-image', 'ConversationController@chat_image');
        });

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('{id}', 'FavoriteController@list');
            Route::post('add', 'FavoriteController@add');
            Route::post('remove', 'FavoriteController@remove');
        });
        Route::group(['prefix' => 'wish-list'], function () {
            Route::get('{id}', 'WishlistController@wish_list');
            Route::post('add', 'WishlistController@add_to_wishlist');
            Route::post('remove', 'WishlistController@remove_from_wishlist');
        });
    });
    
    Route::group(['prefix'=>'queries'], function() {
            Route::get('get_all_queries', 'QueryController@get_all_queries');
            Route::get('get_queries', 'QueryController@get_queries');
            Route::post('send_request', 'QueryController@send_request');
            
             Route::get('get_all_user_queries', 'QueryController@get_all_user_queries');
            Route::get('get_user_queries', 'QueryController@get_user_queries');
            Route::get('get_all_rider_queries', 'QueryController@get_all_rider_queries');
            Route::get('get_rider_queries', 'QueryController@get_rider_queries');
            Route::post('send_rider_request', 'QueryController@send_rider_request');
            Route::post('send_vendor_request', 'QueryController@send_vendor_request');
            Route::get('get_all_vendor_queries', 'QueryController@get_all_vendor_queries');
    });

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'BannerController@get_banners');
    });

    Route::group(['prefix' => 'campaigns'], function () {
        Route::get('basic', 'CampaignController@get_basic_campaigns');
        Route::get('basic-campaign-details', 'CampaignController@basic_campaign_details');
        Route::get('item', 'CampaignController@get_item_campaigns');
    });

    Route::group(['prefix' => 'coupon', 'middleware' => 'auth:api'], function () {
        Route::get('list', 'CouponController@list');
        Route::post('apply', 'CouponController@apply');
    });
    
    Route::group(['prefix' => 'report'], function () {
        Route::post('send_report', 'OrderReportController@order_reports_store');
    });
});

Route::group(['prefix' => 'report','namespace'=> 'Api\V1'], function () {
    Route::post('send_review', 'OrderController@orderReview');
});
Route::post('order_shipping_charges', [OrderController::class,'order_shipping_charges']);

// D:\xampp\htdocs\foodie.junaidali.tk\foodie.junaidali.tk\app\Http\Controllers\Api\V1\OrderController.php
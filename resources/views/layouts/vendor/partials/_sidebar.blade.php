<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                <div class="sidebar-logo-container">
                    
                     <!-- Logo -->
                @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                <a class="navbar-brand d-block p-0" href="{{route('vendor.dashboard')}}" aria-label="Front">
                    <img class="navbar-brand-logo sidebar--logo-design"
                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                    <img class="navbar-brand-logo-mini sidebar--logo-design-2" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                </a>
                <!-- End Logo -->
                    
                    <!--@php($restaurant_data=\App\CentralLogics\Helpers::get_restaurant_data())-->
                    <!--<a class="navbar-brand pt-0 pb-0" href="{{route('vendor.dashboard')}}" aria-label="Front">-->
                    <!--    <img class="navbar-brand-logo"-->
                    <!--        onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"-->
                    <!--        src="{{asset('storage/app/public/restaurant/'.$restaurant_data->logo)}}"-->
                    <!--        alt="Logo">-->
                    <!--    <img class="navbar-brand-logo-mini"-->
                    <!--        onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"-->
                    <!--        src="{{asset('storage/app/public/restaurant/'.$restaurant_data->logo)}}" alt="Logo">-->
                    <!--    <div class="ps-2">-->
                    <!--        <h6>-->
                    <!--            {{\Illuminate\Support\Str::limit($restaurant_data->name,15)}}-->
                    <!--        </h6>-->
                    <!--    </div>-->
                    <!--</a>-->
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>
                <div class="navbar-nav-wrap-content-left ml-auto d-none d-xl-block">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content text-capitalize bg--title">
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="pt-4"></li>
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.dashboard')}}" title="{{translate('messages.dashboard')}}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.dashboard')}}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('pos'))
                    <!-- POS -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/pos')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.pos.index')}}" title="{{translate('POS')}}"
                        >
                            <i class="tio-shopping nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.pos')}}</span>
                        </a>
                    </li>
                    <!-- End POS -->
                    @endif

                    <li class="nav-item">
                        <small
                            class="nav-subtitle">Promotions</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                     @if(\App\CentralLogics\Helpers::get_business_id()  == '2')
                     @if(\App\CentralLogics\Helpers::employee_module_permission_check('Business Slider'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/business_slider*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.business_slider.list')}}" title="{{translate('messages.Business Slider')}}"
                        >
                            <i class="tio-circle nav-indicator-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.Business Slider')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    @endif
                    <!-- Campaign -->
                    <!--@if(\App\CentralLogics\Helpers::employee_module_permission_check('campaign'))-->
                    <!--<li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/campaign*')?'active':''}}">-->
                    <!--    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Campaign')}}">-->
                    <!--        <i class="tio-image nav-icon"></i>-->
                    <!--        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.campaign')}}</span>-->
                    <!--    </a>-->
                    <!--    <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/campaign*')?'d-block':'initial-hidden'}}">-->
                    <!--        <li class="nav-item {{Request::is('vendor-panel/campaign/list')?'active':''}}">-->
                    <!--            <a class="nav-link " href="{{route('vendor.campaign.list')}}" title="{{translate('messages.basic_campaign')}}">-->
                    <!--                <span class="tio-circle nav-indicator-icon"></span>-->
                    <!--                <span class="text-truncate">{{translate('messages.basic_campaign')}}</span>-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--        <li class="nav-item {{Request::is('vendor-panel/campaign/item/list')?'active':''}}">-->
                    <!--            <a class="nav-link " href="{{route('vendor.campaign.itemlist')}}" title="{{translate('messages.food_campaign')}}">-->
                    <!--                <span class="tio-circle nav-indicator-icon"></span>-->
                    <!--                <span class="text-truncate">{{translate('food_campaign')}}</span>-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!-- End Campaign -->

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('order'))
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{translate('messages.order')}} {{translate('messages.section')}}">{{translate('messages.order')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Order -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/order*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                            title="{{translate('messages.orders')}}">
                            <i class="tio-shopping-cart nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.orders')}}
                            </span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/order*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('vendor-panel/order/list/all')?'active':''}}">
                                <a class="nav-link" href="{{route('vendor.order.list',['all'])}}" title="{{translate('messages.all')}} {{translate('messages.order')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.all')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                                                                       {{\App\Models\Order::where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())->whereNotNull('order_status')->OrderScheduledIn(30)->count()}}

                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/pending')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.order.list',['pending'])}}" title="{{translate('messages.pending')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.pending')}} {{(config('order_confirmation_model') == 'restaurant' || \App\CentralLogics\Helpers::get_restaurant_data()->self_delivery_system)?'':translate('messages.')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            <!--@if(config('order_confirmation_model') == 'restaurant' || \App\CentralLogics\Helpers::get_restaurant_data()->self_delivery_system)-->
                                            <!--{{\App\Models\Order::where(['order_status'=>'pending','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->OrderScheduledIn(30)->count()}}-->
                                            <!--@else-->
                                            <!--{{\App\Models\Order::where(['order_status'=>'pending','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id(), 'order_type'=>'take_away'])->Notpos()->OrderScheduledIn(30)->count()}}-->
                                            <!--@endif-->
                                            
                                            {{\App\Models\Order::whereIn('order_status',['pending', 'take_away'])->Notpos()->whereNotNull('pending')->where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())->OrderScheduledIn(30)->count()}}

                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('vendor-panel/order/list/confirmed')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.order.list',['confirmed'])}}" title="{{translate('messages.confirmed')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.confirmed')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Models\Order::whereIn('order_status',['confirmed', 'accepted'])->Notpos()->whereNotNull('confirmed')->where('restaurant_id', \App\CentralLogics\Helpers::get_restaurant_id())->OrderScheduledIn(30)->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('vendor-panel/order/list/cooking')?'active':''}}">
                                <a class="nav-link" href="{{route('vendor.order.list',['cooking'])}}" title="{{translate('messages.cooking')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.cooking')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'processing', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/ready_for_delivery')?'active':''}}">
                                <a class="nav-link" href="{{route('vendor.order.list',['ready_for_delivery'])}}" title="{{translate('Ready For Delivery')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.ready_for_delivery')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'handover', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/food_on_the_way')?'active':''}}">
                                <a class="nav-link" href="{{route('vendor.order.list',['food_on_the_way'])}}" title="{{translate('Food On The Way')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.food_on_the_way')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'picked_up', 'restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/delivered')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.order.list',['delivered'])}}"  title="{{translate('Delivered')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.delivered')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Models\Order::where(['order_status'=>'delivered','restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/refunded')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.order.list',['refunded'])}}"  title="{{translate('Refunded')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.refunded')}}
                                            <span class="badge badge-soft-danger bg-light badge-pill ml-1">
                                            {{\App\Models\Order::Refunded()->where(['restaurant_id'=>\App\CentralLogics\Helpers::get_restaurant_id()])->Notpos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/order/list/scheduled')?'active':''}}">
                                <a class="nav-link" href="{{route('vendor.order.list',['scheduled'])}}" title="{{translate('messages.scheduled')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('messages.scheduled')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Models\Order::where('restaurant_id',\App\CentralLogics\Helpers::get_restaurant_id())->Notpos()->Scheduled()->where(function($q){
                                                if(config('order_confirmation_model') == 'restaurant' || \App\CentralLogics\Helpers::get_restaurant_data()->self_delivery_system)
                                                {
                                                    $q->whereNotIn('order_status',['failed','canceled', 'refund_requested', 'refunded']);
                                                }
                                                else
                                                {
                                                    $q->whereNotIn('order_status',['pending','failed','canceled', 'refund_requested', 'refunded'])->orWhere(function($query){
                                                        $query->where('order_status','pending')->where('order_type', 'take_away');
                                                    });
                                                }

                                            })->count()}}
                                        </span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- End Order -->
                    @endif
                    <li class="nav-item">
                        <small
                            class="nav-subtitle">{{translate('messages.food')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- End AddOn -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('food'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/category*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                            href="javascript:" title="{{translate('messages.categories')}}"
                        >
                            <i class="tio-category nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.categories')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/category*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('vendor-panel/category/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.category.add')}}"
                                    title="{{translate('messages.category')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.category')}}</span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('vendor-panel/category/sub-category-list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.category.add-sub-category')}}"
                                    title="{{translate('messages.sub_category')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.sub_category')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Food -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/food*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Food')}}"
                        >
                            <i class="tio-premium-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.foods')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/food*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('vendor-panel/food/add-new')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.add-new')}}"
                                     title="{{translate('Add New Food')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('messages.add')}} {{translate('messages.new')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/food/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.list')}}"  title="{{translate('Food List')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.list')}}</span>
                                </a>
                            </li>
                            @if(\App\CentralLogics\Helpers::get_restaurant_data()->food_section)
                            <li class="nav-item {{Request::is('vendor-panel/food/bulk-import')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.bulk-import')}}"
                                     title="{{translate('Bulk Import')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_import')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/food/bulk-export')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.food.bulk-export-index')}}"
                                     title="{{translate('Bulk Export')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_export')}}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <!-- End Food -->
                    
                    
                    
                    <!-- Deals -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/deal*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:" title="{{translate('Deal')}}"
                        >
                            <i class="tio-premium-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.Deals')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/deal*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('vendor-panel/deal/add-new')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.deal.add-new')}}"
                                     title="{{translate('Add New Deal')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('messages.add')}} {{translate('messages.new')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/deal/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.deal.list')}}"  title="{{translate('Deal List')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.list')}}</span>
                                </a>
                            </li>
                            @if(\App\CentralLogics\Helpers::get_restaurant_data()->deal_section)
                            <li class="nav-item {{Request::is('vendor-panel/deal/bulk-import')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.deal.bulk-import')}}"
                                     title="{{translate('Bulk Import')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_import')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/deal/bulk-export')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.deal.bulk-export-index')}}"
                                     title="{{translate('Bulk Export')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate text-capitalize">{{translate('messages.bulk_export')}}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <!-- End Deal -->
                    @endif
                    <!-- AddOn -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('addon'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/addon*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.addon.add-new')}}" title="{{translate('messages.addons')}}"
                        >
                            <i class="tio-add-circle-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.addons')}}
                            </span>
                        </a>
                    </li>
                    @endif

                    <!-- DeliveryMan -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('deliveryman'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                   title="{{translate('messages.deliveryman')}} {{translate('messages.section')}}">{{translate('messages.deliveryman')}} {{translate('messages.management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/delivery-man/add')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.add')}}"
                               title="{{translate('messages.add_delivery_man')}}"
                            >
                                <i class="tio-running nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.add_delivery_man')}}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/delivery-man/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.list')}}"
                               title="{{translate('messages.deliveryman')}} {{translate('messages.list')}}"
                            >
                                <i class="tio-filter-list nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.deliverymen')}} {{translate('messages.list')}}
                                </span>
                            </a>
                        </li>

                        {{--<li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/delivery-man/reviews/list')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('vendor.delivery-man.reviews.list')}}" title="{{translate('messages.reviews')}}"
                            >
                                <i class="tio-star-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.reviews')}}
                                </span>
                            </a>
                        </li>--}}
                    @endif
                <!-- End DeliveryMan -->


                    <!-- Business Section-->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                                title="{{translate('messages.business')}} {{translate('messages.section')}}">{{translate('messages.business')}} {{translate('messages.management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('restaurant_setup'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/business-settings/restaurant-setup')?'active':''}}">
                        <a class="nav-link " href="{{route('vendor.business-settings.restaurant-setup')}}" title="{{translate('messages.business')}} {{translate('messages.config')}}"
                        >
                            <span class="tio-settings nav-icon"></span>
                            <span
                                class="text-truncate">{{translate('messages.Business')}} {{translate('messages.config')}}</span>
                        </a>
                    </li>
                    @endif

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('my_shop'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/restaurant/view')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.shop.view')}}"
                            title="{{translate('My Business')}}">
                            <i class="tio-home nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.my_business')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('bank_info'))
                    <!-- Business Settings -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/profile*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.profile.bankView')}}"
                            title="{{translate('messages.my')}} {{translate('messages.bank_info')}}">
                            <i class="tio-shop nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.my')}} {{translate('messages.bank_info')}}
                            </span>
                        </a>
                    </li>
                    @endif


                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('wallet'))
                    <!-- RestaurantWallet -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/wallet*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.wallet.index')}}" title="{{translate('messages.my')}} {{translate('messages.wallet')}}"
                        >
                            <i class="tio-table nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.my')}} {{translate('messages.wallet')}}</span>
                        </a>
                    </li>
                    @endif
                    <!-- End RestaurantWallet -->
                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('reviews'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/reviews')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{route('vendor.reviews')}}" title="{{translate('messages.reviews')}}"
                        >
                            <i class="tio-star-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.reviews')}}
                            </span>
                        </a>
                    </li>
                    @endif
                    <!-- End RestaurantWallet -->
                    <!--@if(\App\CentralLogics\Helpers::employee_module_permission_check('chat'))-->
                    <!--<li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/message*')?'active':''}}">-->
                    <!--    <a class="js-navbar-vertical-aside-menu-link nav-link"-->
                    <!--        href="{{route('vendor.message.list')}}" title="{{translate('messages.chat')}}"-->
                    <!--    >-->
                    <!--        <i class="tio-chat nav-icon"></i>-->
                    <!--        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">-->
                    <!--            {{translate('messages.chat')}}-->
                    <!--        </span>-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!-- End Business Settings -->

                    <!-- Employee-->
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{translate('messages.employee')}} {{translate('messages.section')}}">{{translate('messages.employee')}} {{translate('messages.section')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('custom_role'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/custom-role*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{route('vendor.custom-role.create')}}"
                        title="{{translate('messages.employee')}} {{translate('messages.Role')}}">
                            <i class="tio-incognito nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.employee')}} {{translate('messages.Role')}}</span>
                        </a>
                    </li>
                    @endif

                    @if(\App\CentralLogics\Helpers::employee_module_permission_check('employee'))
                    <li class="navbar-vertical-aside-has-menu {{Request::is('vendor-panel/employee*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                        title="{{translate('messages.employees')}}">
                            <i class="tio-user nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('messages.employees')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub {{Request::is('vendor-panel/employee*')?'d-block':'initial-hidden'}}">
                            <li class="nav-item {{Request::is('vendor-panel/employee/add-new')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.employee.add-new')}}" title="{{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.Employee')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.employee')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('vendor-panel/employee/list')?'active':''}}">
                                <a class="nav-link " href="{{route('vendor.employee.list')}}" title="{{translate('messages.Employee')}} {{translate('messages.list')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('messages.list')}}</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                    <!-- End Employee -->

                    <li class="nav-item pt-100px">

                    </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


{{--<script>
    $(document).ready(function () {
        $('.navbar-vertical-content').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 'slow');
    });
</script>--}}

@push('script_2')
<script>
    $(window).on('load' , function() {
        if($(".navbar-vertical-content li.active").length) {
            $('.navbar-vertical-content').animate({
                scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
            }, 100);
        }
        });
</script>
@endpush

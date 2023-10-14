<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar__brand-wrapper navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                <a class="navbar-brand d-block p-0" href="{{ route('admin.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo sidebar--logo-design"
                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                    <img class="navbar-brand-logo-mini sidebar--logo-design-2" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                    class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left d-none d-xl-block">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template='<div class="tooltip d-none" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                            data-toggle="tooltip" data-placement="right" title="Expand"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content bg--title" id="navbar-vertical-content">
                <!-- Search Form -->
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" id="search" class="form-control form--control" placeholder="{{ translate('Search Menu...') }}">
                    </div>
                </form>
                <!-- Search Form -->
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.dashboard') }}" title="{{ translate('messages.dashboard') }}">
                            <i class="tio-dashboard-vs nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->
                    <!--@if (\App\CentralLogics\Helpers::module_permission_check('pos'))-->
                    <!--    {{-- <li class="nav-item">-->
                    <!--        <small class="nav-subtitle">{{ translate('POS') }}-->
                    <!--            {{ translate('System') }}</small>-->
                    <!--        <small class="tio-more-horizontal nav-subtitle-replacer"></small>-->
                    <!--    </li> --}}-->
                        <!-- POS -->
                    <!--    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/pos') ? 'active' : '' }}">-->
                    <!--        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.pos.index') }}" title="{{ translate('messages.pos') }}">-->
                    <!--            <i class="tio-receipt nav-icon"></i>-->
                    <!--            <span-->
                    <!--                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.pos') }}</span>-->
                    <!--        </a>-->
                    <!--    </li>-->
                        <!-- End POS -->
                    <!--@endif-->
                     <!-- absher express -->
                   

  @if (\App\CentralLogics\Helpers::module_permission_check('order'))
                        <li class="nav-item">
                            <small class="nav-subtitle">{{ translate('messages.order') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/order*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.orders') }}">
                                <i class="tio-file-text-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.orders') }}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/order*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/order/list/all') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.order.list', ['all']) }}"
                                        title="{{ translate('messages.all') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.all') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Notpos()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/scheduled') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.order.list', ['scheduled']) }}"
                                        title="{{ translate('messages.scheduled') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.scheduled') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Scheduled()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/pending') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['pending']) }}"
                                        title="{{ translate('messages.pending') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.pending') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Pending()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/order/list/accepted') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['accepted']) }}"
                                        title="{{ translate('messages.accepted') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.accepted') }}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{ \App\Models\Order::AccepteByDeliveryman()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/processing') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['processing']) }}"
                                        title="{{ translate('messages.processing') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.processing') }}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{ \App\Models\Order::Preparing()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/order/list/food_on_the_way') ? 'active' : '' }}">
                                    <a class="nav-link text-capitalize"
                                        href="{{ route('admin.order.list', ['food_on_the_way']) }}"
                                        title="{{ translate('messages.foodOnTheWay') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.foodOnTheWay') }}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{ \App\Models\Order::FoodOnTheWay()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/delivered') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['delivered']) }}"
                                        title="{{ translate('messages.delivered') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.delivered') }}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{ \App\Models\Order::Delivered()->Notpos()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/canceled') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['canceled']) }}"
                                        title="{{ translate('messages.canceled') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.canceled') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::Canceled()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/failed') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['failed']) }}"
                                        title="{{ translate('messages.payment') }} {{ translate('messages.failed') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container text-capitalize">
                                            {{ translate('messages.payment') }} {{ translate('messages.failed') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::failed()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/refunded') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['refunded']) }}"
                                        title="{{ translate('messages.refunded') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.refunded') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::Refunded()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order dispachment -->
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/dispatch/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.dispatchManagement') }}">
                                <i class="tio-clock nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.dispatchManagement') }}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/dispatch*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/dispatch/list/searching_for_deliverymen') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.dispatch.list', ['searching_for_deliverymen']) }}"
                                        title="{{ translate('messages.searchingDM') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('messages.searchingDM') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::SearchingForDeliveryman()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/dispatch/list/on_going') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.dispatch.list', ['on_going']) }}"
                                        title="{{ translate('messages.ongoingOrders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.ongoingOrders') }}
                                            <span class="badge badge-soft-dark bg-light badge-pill ml-1">
                                                {{ \App\Models\Order::Ongoing()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order dispachment End-->
                    @endif
                    <!-- End Orders -->
                        <!-- Order Report Center-->
                    @if (\App\CentralLogics\Helpers::module_permission_check('order'))
                        <li class=" {{ Request::is('admin/report*') ? 'active' : '' }}">
                            <a class=" nav-link" href="{{ route('admin.report.order-reports-index') }}"
                                title="{{ translate('messages.order') }} {{ translate('messages.report_center') }}">
                                <i class="tio-warning nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.order') }} {{ translate('messages.report') }}
                                    {{ translate('messages.center') }}</span>
                            </a>
                        </li>
                    @endif
                        <!-- Order Report Center End -->
                        
                        
                          <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle" title="{{ translate('messages.employee_handle') }}">{{ translate('Promotions') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- Campaign -->
                    <!--@if (\App\CentralLogics\Helpers::module_permission_check('campaign'))-->
                    <!--    <li-->
                    <!--        class="navbar-vertical-aside-has-menu {{ Request::is('admin/campaign*') ? 'active' : '' }}">-->
                    <!--        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"-->
                    <!--            title="{{ translate('messages.campaigns') }}">-->
                    <!--            <i class="tio-notice nav-icon"></i>-->
                    <!--            <span-->
                    <!--                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.campaigns') }}</span>-->
                    <!--        </a>-->
                    <!--        <ul class="js-navbar-vertical-aside-submenu nav nav-sub"-->
                    <!--            style="display: {{ Request::is('admin/campaign*') ? 'block' : 'none' }}; background: #3f0d4b !important">-->

                    <!--            <li class="nav-item {{ Request::is('admin/campaign/basic/*') ? 'active' : '' }}">-->
                    <!--                <a class="nav-link " href="{{ route('admin.campaign.list', 'basic') }}"-->
                    <!--                    title="{{ translate('messages.basic_campaign') }}">-->
                    <!--                    <span class="tio-circle nav-indicator-icon"></span>-->
                    <!--                    <span class="text-truncate">{{ translate('messages.basic_campaign') }}</span>-->
                    <!--                </a>-->
                    <!--            </li>-->
                    <!--            <li class="nav-item {{ Request::is('admin/campaign/item/*') ? 'active' : '' }}">-->
                    <!--                <a class="nav-link " href="{{ route('admin.campaign.list', 'item') }}"-->
                    <!--                    title="{{ translate('messages.food_campaign') }}">-->
                    <!--                    <span class="tio-circle nav-indicator-icon"></span>-->
                    <!--                    <span class="text-truncate">{{ translate('messages.food_campaign') }}</span>-->
                    <!--                </a>-->
                    <!--            </li>-->
                    <!--        </ul>-->
                    <!--    </li>-->
                    <!--@endif-->
                       @if (\App\CentralLogics\Helpers::module_permission_check('business_slider'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/business_slider*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business_slider.list') }}" title="{{ translate('messages.business_slider') }}">
                                <i class="tio-bookmark nav-icon side-nav-icon--design"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Business_Slider') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Campaign -->
                    <!-- Banner -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('banner'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/banner*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.banner.add-new') }}" title="{{ translate('messages.banners') }}">
                                <i class="tio-bookmark nav-icon side-nav-icon--design"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.banners') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Banner -->
                    <!-- Coupon -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('coupon'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/coupon*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.coupon.add-new') }}" title="{{ translate('messages.coupons') }}">
                                <i class="tio-ticket nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.coupons') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Coupon -->
                    <!-- Notification -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('notification'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/notification*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.notification.add-new') }}"
                                title="{{ translate('messages.push') }} {{ translate('messages.notification') }}">
                                <i class="tio-notifications-on nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.push') }} {{ translate('messages.notification') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    <!-- End Notification -->
  <li class="nav-item">
                        <small class="nav-subtitle" title="{{ translate('messages.General Section') }}">{{ translate('General') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                     @if (\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/service*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business-settings.absher-express') }}"
                                title="{{ translate('messages.deliveryman') }} Payments">
                                <i class="tio-bike nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Absher_Express') }}
                                </span>
                            </a>
                        </li>
                    @endif
                     @if (\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))
                        
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/service*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.business-settings.services-add') }}"
                            title="{{ translate('messages.deliveryman') }} Payments">
                            <i class="tio-settings nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Services') }}
                            </span>
                        </a>
                    </li>
                    @endif
 @if (\App\CentralLogics\Helpers::module_permission_check('Support Center'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/userquires*') ? 'active' : ''  }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.Help Center') }}">
                                <i class="tio-add-circle-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Support Center') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/userquires*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/query/list') ? 'active' : '' }}">
                                    <a class="nav-link "  href="{{ route('admin.query.home') }}"
                                        title="{{ translate('messages.Queries') }} {{ translate('messages.Setup') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.Queries Setup') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/userquires/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.userquires.list') }}"
                                        title="{{ translate('User Queries') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.User Queries') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/userquires/rider-list*') ? 'active' : '' }}">
                                    <a class="nav-link "   href="{{ route('admin.userquires.rider_list') }}"
                                        title="{{ translate('Rider Queries') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.Rider Queries') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::module_permission_check('Help Center'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/about*') ? 'active' : ''  }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.Help Center') }}">
                                <i class="tio-add-circle-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Help Center') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/about*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/about/list') ? 'active' : '' }}">
                                    <a class="nav-link "  href="{{ route('admin.about.list') }}"
                                        title="{{ translate('messages.About') }} {{ translate('messages.Us') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.About Us') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/terms_and_conditions/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.terms_and_conditions.list') }}"
                                        title="{{ translate('Terms And Conditions') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.Terms And Conditions') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/privacy_policy*') ? 'active' : '' }}">
                                    <a class="nav-link "   href="{{ route('admin.privacy_policy.list') }}"
                                        title="{{ translate('Privacy Policy') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.Privacy Policy') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    <!-- End absher express -->
                  
                    <!-- Orders -->
                  
                    <!-- Restaurant -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.restaurant') }} {{ translate('messages.section') }}">{{ translate('messages.Business') }}
                            {{ translate('messages.management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
 @if (\App\CentralLogics\Helpers::module_permission_check('country'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/country*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.country.home') }}" title="{{ translate('messages.Country') }} {{ translate('messages.setup') }}">
                                <i class="tio-poi-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.Country') }} {{ translate('messages.setup') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('city'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/city*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.city.home') }}" title="{{ translate('messages.City') }} {{ translate('messages.setup') }}">
                                <i class="tio-poi-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.city') }} {{ translate('messages.setup') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('zone'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/zone*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.zone.home') }}" title="{{ translate('messages.zone') }} {{ translate('messages.setup') }}">
                                <i class="tio-poi-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.zone') }} {{ translate('messages.setup') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('restaurant'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/vendor*') && !Request::is('admin/vendor/withdraw_list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.restaurants') }}">
                                <i class="tio-restaurant nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Business') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/vendor*') && !Request::is('admin/vendor/withdraw_list') ? 'block' : 'none' }}">
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/vendor/add') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.vendor.add') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.vendor') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('messages.add') }} {{ translate('messages.vendor') }}
                                        </span>
                                    </a>
                                </li>

                                <li class="navbar-item {{ Request::is('admin/vendor/list') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.vendor.list') }}"
                                        title="{{ translate('messages.restaurants') }} {{ translate('list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.Business') }}
                                            {{ translate('list') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/vendor/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.vendor.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/vendor/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.vendor.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    <!-- End Restaurant -->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.product') }} {{ translate('messages.section') }}">{{ translate('messages.product') }}
                            {{ translate('messages.management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Category -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('category'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/category*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.categories') }}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.categories') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/category*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/category/add') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.add') }}"
                                        title="{{ translate('messages.category') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.category') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/category/add-sub-category') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.add-sub-category') }}"
                                        title="{{ translate('messages.sub_category') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.sub_category') }}</span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item {{Request::is('admin/category/add-sub-sub-category')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.add-sub-sub-category')}}"
                                        title="add new sub sub category">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Sub-Sub-Category</span>
                                    </a>
                                </li> --}}
                                <li class="nav-item {{ Request::is('admin/category/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/category/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.category.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Category -->

                    <!-- Attributes -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('attribute'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/attribute*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.attribute.add-new') }}"
                                title="{{ translate('messages.attributes') }}">
                                <i class="tio-apps nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.attributes') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    <!-- End Attributes -->

                    <!-- AddOn -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('addon'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/addon*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.addons') }}">
                                <i class="tio-add-circle-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.addons') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/addon*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/addon/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.add-new') }}"
                                        title="{{ translate('messages.addon') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/addon/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/addon/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End AddOn -->
                    <!-- Food -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('food'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/food*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.foods') }}">
                                <i class="tio-fastfood nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.foods') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/food*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/food/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.add-new') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.new') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add') }}
                                            {{ translate('messages.new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.list') }}"
                                        title="{{ translate('messages.food') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/reviews') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.reviews') }}"
                                        title="{{ translate('messages.review') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.review') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Food -->
                    <!-- DeliveryMan -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('deliveryman'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.deliveryman') }} {{ translate('messages.section') }}">{{ translate('messages.deliveryman') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/add') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.delivery-man.add') }}"
                                title="{{ translate('messages.add_delivery_man') }}">
                                <i class="tio-running nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.add_delivery_man') }}
                                </span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.delivery-man.list') }}"
                                title="{{ translate('messages.deliveryman') }} {{ translate('messages.list') }}">
                                <i class="tio-filter-list nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.deliveryman') }} {{ translate('messages.list') }}
                                </span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/reviews/list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.delivery-man.reviews.list') }}"
                                title="{{ translate('messages.reviews') }}">
                                <i class="tio-star-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.reviews') }}
                                </span>
                            </a>
                        </li>
                         <!--HAA-27_03_2023-shift_functionality-start-->
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/shifts') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.delivery-man.shifts') }}"
                                title="{{ translate('messages.shifts') }}">
                                <i class="tio-clap nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.shifts') }}
                                </span>
                            </a>
                        </li>
                        <!--HAA-27_03_2023-shift_functionality-start-->
                    @endif
                    <!-- End DeliveryMan -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('customerList'))
                        <!-- Custommer -->
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.customer') }} {{ translate('messages.section') }}">{{ translate('messages.customer') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/list') || Request::is('admin/customer/view*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.list') }}" title="{{translate('Customer List') }}">
                                <i class="tio-poi-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.customer') }} {{ translate('messages.list') }}
                                </span>
                            </a>
                        </li>

                        <!--<li-->
                        <!--    class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/wallet*') ? 'active' : '' }}">-->

                        <!--    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"-->
                        <!--        title="{{translate('Customer Wallet') }}">-->
                        <!--        <i class="tio-wallet nav-icon"></i>-->
                        <!--        <span-->
                        <!--            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate  text-capitalize">-->
                        <!--            {{ translate('messages.customer') }} {{ translate('messages.wallet') }}-->
                        <!--        </span>-->
                        <!--    </a>-->

                        <!--    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"-->
                        <!--        style="display: {{ Request::is('admin/customer/wallet*') ? 'block' : 'none' }}">-->
                        <!--        <li-->
                        <!--            class="nav-item {{ Request::is('admin/customer/wallet/add-fund') ? 'active' : '' }}">-->
                        <!--            <a class="nav-link " href="{{ route('admin.customer.wallet.add-fund') }}"-->
                        <!--                title="{{ translate('messages.add_fund') }}">-->
                        <!--                <span class="tio-circle nav-indicator-icon"></span>-->
                        <!--                <span-->
                        <!--                    class="text-truncate text-capitalize">{{ translate('messages.add_fund') }}</span>-->
                        <!--            </a>-->
                        <!--        </li>-->

                        <!--        <li-->
                        <!--            class="nav-item {{ Request::is('admin/customer/wallet/report*') ? 'active' : '' }}">-->
                        <!--            <a class="nav-link " href="{{ route('admin.customer.wallet.report') }}"-->
                        <!--                title="{{ translate('messages.report') }}">-->
                        <!--                <span class="tio-circle nav-indicator-icon"></span>-->
                        <!--                <span-->
                        <!--                    class="text-truncate text-capitalize">{{ translate('messages.report') }}</span>-->
                        <!--            </a>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/loyalty-point*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.customer_loyalty_point') }}">
                                <i class="tio-medal nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate  text-capitalize">
                                    {{ translate('messages.customer_loyalty_point') }}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/customer/loyalty-point*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/customer/loyalty-point/report*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.customer.loyalty-point.report') }}"
                                        title="{{ translate('messages.report') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.report') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/subscribed') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.subscribed') }}" title="{{translate('Subscribed Emails')}}">
                                <i class="tio-email-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.subscribed_mail_list') }}
                                </span>
                            </a>
                        </li>--}}
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/settings') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.settings') }}"
                                title="{{ translate('messages.Customer') }} {{ translate('messages.settings') }}">
                                <i class="tio-settings nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.Customer') }} {{ translate('messages.settings') }}
                                </span>
                            </a>
                        </li>
                        </li>
                        <!--<li-->
                        <!--    class="navbar-vertical-aside-has-menu {{ Request::is('admin/message/list') ? 'active' : '' }}">-->
                        <!--    <a class="js-navbar-vertical-aside-menu-link nav-link"-->
                        <!--        href="{{ route('admin.message.list') }}"-->
                        <!--        title="{{ translate('messages.Customer') }} {{ translate('messages.chat') }}">-->
                        <!--        <i class="tio-chat nav-icon"></i>-->
                        <!--        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">-->
                        <!--            {{ translate('messages.Customer') }} {{ translate('messages.chat') }}-->
                        <!--        </span>-->
                        <!--    </a>-->
                        <!--</li>-->
                    @endif


                    <!-- End Custommer -->


                    <!-- Employee-->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.employee_handle') }}">{{ translate('Employee Management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if (\App\CentralLogics\Helpers::module_permission_check('custom_role'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/custom-role*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.custom-role.create') }}"
                                title="{{ translate('messages.employee') }} {{ translate('messages.Role') }}">
                                <i class="tio-incognito nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employee') }}
                                    {{ translate('messages.Role') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::module_permission_check('employee'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/employee*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('Employees') }}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employees') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/employee*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/employee/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.employee.add-new') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.new') }} {{ translate('messages.Employee') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add') }}
                                            {{ translate('messages.new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/employee/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.employee.list') }}"
                                        title="{{ translate('messages.Employee') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    <!-- End Employee -->



                    <!-- Report -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('report'))
                        {{--<li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.report_and_analytics') }}">{{ translate('messages.report') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/day-wise-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.day-wise-report') }}"
                                title="{{ translate('messages.day_wise_report') }}">
                                <span class="tio-report nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.day_wise_report') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/food-wise-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.food-wise-report') }}"
                                title="{{ translate('messages.food_wise_report') }}">
                                <span class="tio-report nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.food_wise_report') }}</span>
                            </a>
                        </li>--}}
                    @endif
                    <!-- Report -->
                    <!-- Business Settings -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.business') }} {{ translate('messages.settings') }}">{{ translate('messages.business') }}
                                {{ translate('messages.settings') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/business-setup') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.business-setup') }}"
                                title="{{ translate('messages.business') }} {{ translate('messages.setup') }}">
                                <span class="tio-settings nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.business') }}
                                    {{ translate('messages.setup') }}</span>
                            </a>
                        </li>

                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/social-media') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.social-media.index') }}"
                                title="{{ translate('messages.Social Media') }}">
                                <span class="tio-slack nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.Social Media') }}</span>
                            </a>
                        </li>--}}

                        <!--<li-->
                        <!--    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/payment-method') ? 'active' : '' }}">-->
                        <!--    <a class="nav-link " href="{{ route('admin.business-settings.payment-method') }}"-->
                        <!--        title="{{ translate('messages.payment') }} {{ translate('messages.methods') }}">-->
                        <!--        <span class="tio-atm nav-icon"></span>-->
                        <!--        <span class="text-truncate">{{ translate('messages.payment') }}-->
                        <!--            {{ translate('messages.methods') }}</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <!--<li-->
                        <!--    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/mail-config') ? 'active' : '' }}">-->
                        <!--    <a class="nav-link " href="{{ route('admin.business-settings.mail-config') }}"-->
                        <!--        title="{{ translate('messages.mail') }} {{ translate('messages.config') }}">-->
                        <!--        <span class="tio-email nav-icon"></span>-->
                        <!--        <span class="text-truncate">{{ translate('messages.mail') }}-->
                        <!--            {{ translate('messages.config') }}</span>-->
                        <!--    </a>-->
                        <!--</li>-->
                        <!--<li-->
                        <!--    class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/sms-module') ? 'active' : '' }}">-->
                        <!--    <a class="nav-link " href="{{ route('admin.business-settings.sms-module') }}"-->
                        <!--        title="{{ translate('messages.sms') }} {{ translate('messages.module') }}">-->
                        <!--        <span class="tio-sms-active nav-icon"></span>-->
                        <!--        <span class="text-truncate">{{ translate('messages.sms') }}-->
                        <!--            {{ translate('messages.module') }}</span>-->
                        <!--    </a>-->
                        <!--</li>-->

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/fcm-index') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.fcm-index') }}"
                                title="{{ translate('messages.notification') }} {{ translate('messages.settings') }}">
                                <span class="tio-notifications-on nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.notification') }}
                                    {{ translate('messages.settings') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Business Settings -->

                    <!-- web & adpp Settings -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/pages*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.pages') }} {{ translate('messages.setup') }}">
                                <i class="tio-pages nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.pages') }}
                                    {{ translate('messages.setup') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/business-settings/pages*') ? 'block' : 'none' }}">

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/terms-and-conditions') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.business-settings.terms-and-conditions') }}"
                                        title="{{ translate('messages.terms_and_condition') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.terms_and_condition') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/privacy-policy') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.business-settings.privacy-policy') }}"
                                        title="{{ translate('messages.privacy_policy') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.privacy_policy') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/about-us') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.business-settings.about-us') }}"
                                        title="{{ translate('messages.about_us') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.about_us') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.business') }} {{ translate('messages.settings') }}">{{ translate('messages.system') }}
                                {{ translate('messages.settings') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/theme-settings*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.theme-settings') }}"
                                title="{{ translate('theme_settings') }}">
                                <span class="tio-brush nav-icon"></span>
                                <span class="text-truncate">{{ translate('theme_settings') }}</span>
                            </a>
                        </li>--}}
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/app-settings*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.app-settings') }}"
                                title="{{ translate('messages.app_settings') }}">
                                <span class="tio-android nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.app_settings') }}</span>
                            </a>
                        </li>
                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/landing-page-settings*') ? 'active' : '' }}">
                            <a class="nav-link "
                                href="{{ route('admin.business-settings.landing-page-settings', 'index') }}"
                                title="{{ translate('messages.landing_page_settings') }}">
                                <span class="tio-website nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.landing_page_settings') }}</span>
                            </a>
                        </li>--}}
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/config*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.config-setup') }}"
                                title="{{ translate('messages.third_party_apis') }}">
                                <span class="tio-key nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.third_party_apis') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/file-manager*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.file-manager.index') }}"
                                title="{{ translate('messages.gallery') }}">
                                <span class="tio-album nav-icon"></span>
                                <span class="text-truncate text-capitalize">{{ translate('messages.gallery') }}</span>
                            </a>
                        </li>
 @if (\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))
                        
                    <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/timezone*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.business-settings.timezone-add') }}"
                            title="{{ translate('messages.deliveryman') }} Payments">
                            <i class="tio-time nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Time_Zones') }}
                            </span>
                        </a>
                    </li>
                    @endif
                        {{-- <li class="navbar-vertical-aside-has-menu {{Request::is('admin/social-login/view')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.social-login.view')}}">
                                <i class="tio-twitter nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.social_login')}}
                                </span>
                            </a>
                        </li> --}}

                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/recaptcha*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.recaptcha_index') }}"
                                title="{{ translate('messages.reCaptcha') }}">
                                <span class="tio-top-security-outlined nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.reCaptcha') }}</span>
                            </a>
                        </li>--}}
                        {{-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/environment-setup') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business-settings.environment-setup') }}">
                                <i class="tio-labels nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('environment_setup') }}
                                </span>
                            </a>
                        </li> --}}
                        {{--<li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/db-index') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business-settings.db-index') }}" title="{{ translate('clean_database') }}">
                                <i class="tio-cloud nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('clean_database') }}
                                </span>
                            </a>
                        </li>--}}
                    @endif
                    <!-- End web & adpp Settings -->

                    <!-- Business Section-->
                    <!--<li class="nav-item">-->
                    <!--    <small class="nav-subtitle"-->
                    <!--        title="{{ translate('messages.business') }} {{ translate('messages.section') }}">{{ translate('messages.transaction') }}-->
                    <!--        {{ translate('messages.management') }}</small>-->
                    <!--    <small class="tio-more-horizontal nav-subtitle-replacer"></small>-->
                    <!--</li>-->
                    <!-- account -->
                    <!--@if (\App\CentralLogics\Helpers::module_permission_check('account'))-->
                    <!--    <li-->
                    <!--        class="navbar-vertical-aside-has-menu {{ Request::is('admin/account-transaction*') ? 'active' : '' }}">-->
                    <!--        <a class="js-navbar-vertical-aside-menu-link nav-link"-->
                    <!--            href="{{ route('admin.account-transaction.index') }}"-->
                    <!--            title="{{ translate('messages.collect') }} {{ translate('messages.cash') }}">-->
                    <!--            <i class="tio-money nav-icon"></i>-->
                    <!--            <span-->
                    <!--                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.collect') }}-->
                    <!--                {{ translate('messages.cash') }}</span>-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--@endif-->
                    <!-- End account -->
                    <!-- withdraw -->
                    <!--@if (\App\CentralLogics\Helpers::module_permission_check('withdraw_list'))-->
                    <!--    <li-->
                    <!--        class="navbar-vertical-aside-has-menu {{ Request::is('admin/vendor/withdraw*') ? 'active' : '' }}">-->
                    <!--        <a class="js-navbar-vertical-aside-menu-link nav-link"-->
                    <!--            href="{{ route('admin.vendor.withdraw_list') }}"-->
                    <!--            title="{{ translate('messages.restaurant') }} {{ translate('messages.withdraws') }}">-->
                    <!--            <i class="tio-table nav-icon"></i>-->
                    <!--            <span-->
                    <!--                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurant') }}-->
                    <!--                {{ translate('messages.withdraws') }}</span>-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--@endif-->
                    <!-- End withdraw -->

                    <!-- provide_dm_earning -->
                    <!--@if (\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))-->
                    <!--    <li-->
                    <!--        class="navbar-vertical-aside-has-menu {{ Request::is('admin/provide-deliveryman-earnings*') ? 'active' : '' }}">-->
                    <!--        <a class="js-navbar-vertical-aside-menu-link nav-link"-->
                    <!--            href="{{ route('admin.provide-deliveryman-earnings.index') }}"-->
                    <!--            title="{{ translate('messages.deliveryman') }} Payments">-->
                    <!--            <i class="tio-send nav-icon"></i>-->
                    <!--            <span-->
                    <!--                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Delivery Man Payments') }}</span>-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--@endif-->
                    <!-- End provide_dm_earning -->
                    <!--HAA-25_03_2023-timezone-start-->
                   
                    <!--HAA-25_03_2023-timezone-end-->
                    
                    <!--HAA-27_03_2023-service-start-->
                    
                    
                    
                    <!--HAA-27_03_2023-service-start-->
                    
                    


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


@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });
    </script>
    <script>
        var $rows = $('#navbar-vertical-content li');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush

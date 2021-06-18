<div class="left_side_menu_1">
@php
    $per = '\App\Helpers\Helper';

$checkCatalouge = $per::chekStatus('import_catalogue') != false || $per::chekStatus('product_type_list')!= false ||$per::chekStatus('brand_list')!= false  ||
                  $per::chekStatus('product_list')!= false      || $per::chekStatus('addons_list')!= false       ||$per::chekStatus('supplier_list')!= false;

$checkReport  =  $per::chekStatus('product_report') != false    || $per::chekStatus('product_export') != false   ||  $per::chekStatus('category_report') != false ||
                 $per::chekStatus('category_export') != false   || $per::chekStatus('supplier_report') != false  || $per::chekStatus('supplier_export') != false ||
                 $per::chekStatus('brand_report') != false      || $per::chekStatus('brand_export') != false     ||  $per::chekStatus('user_report') != false ||
                 $per::chekStatus('reporting_sales_report') != false      || $per::chekStatus('reporting_inventory_report') != false     ||  $per::chekStatus('reporting_register_closure') != false ||
                 $per::chekStatus('customer_report') != false      || $per::chekStatus('customer_group_report') != false     ||  $per::chekStatus('reporting_register_closure') != false ||
                 $per::chekStatus('user_export') != false       || $per::chekStatus('addon_report') != false     || $per::chekStatus('addon_export') != false;

@endphp

<!-- aside -->
    <aside id="aside" class="app-aside hidden-xs bg-light border-right">

        <div class="aside-wrap">
            <div class="navi-wrap">
                <!-- user -->
                <!-- / user -->
                <!-- nav -->
                <nav ui-nav class="navi clearfix">
					<span class="btn-block text-center mt-lg-3">
                        @if(Auth::user()->store != null && Auth::user()->store->store_logo !== null)
                            <img src="{{ CustomUrl::asset(Auth::user()->store->store_logo) }}"
                                 class="img-responsive p-2 biglogo">
                            <img src="{{ CustomUrl::asset(Auth::user()->store->store_logo) }}"
                                 class="mt-3 mb-3 smalllogo" style="height: auto;width: 62px;">
                        @else
                            <img src="{{ CustomUrl::asset('backoffice/assets/img/logo.png') }}"
                                 class="img-responsive p-2 biglogo">
                            <img src="{{ CustomUrl::asset('backoffice/assets/img/logo-icon.png') }}"
                                 class="mt-3 mb-3 smalllogo">
                        @endif

					</span>
                    <ul class="nav">

                  
                            <li>
                                <a href="{{ route('backoffice.dashboard') }}" class="auto" title="Dashboard"><i
                                        class="icon-grid"></i> <span>@lang('backoffice.dashboard')</span>
                                </a>

                                <!-- <a href="{{url('backoffice/dashboard')}}"></a> -->

                            </li>
                   

                        @if($checkReport)
                        <li>
                            <a href="{{route('reports.index')}}" class="auto" title="Reports">
                                <i class="fa fa-file-text-o"></i>
                                <span>@lang('backoffice.reports')</span>
                            </a>
                            <!-- <ul class="nav nav-sub dk"> -->
                            <!-- <li>
                                    <a href="{{route('stockreport.index')}}">
                                        <span>Stock Report</span>
                                    </a>
                                </li> -->
                                <!-- <li>
                                    <a href="{{route('reports.index')}}">
                                        <span>Reports</span>
                                    </a>
                                </li> -->
                            <!-- <li>
                                    <a href="{{route('customerreport.index')}}">
                                        <span>Customer Report</span>
                                    </a>
                                </li> -->
                            <!-- </ul> -->
                        </li>
                        @endif




                        @if($checkCatalouge)
                            <li>
                                <a href class="auto" title="Catalogue Management">
                                    <i class="icon-notebook"></i>
                                    <span>@lang('backoffice.catelouge_management')</span>
                                </a>
                                <ul class="nav nav-sub dk">
                                    @if( $per::chekStatus('import_catalogue','admin'))
                                        <li>
                                            <a href="{{ route('import.products')}}">
                                                <span>{{ __('backoffice.import_catalogue') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if( $per::chekStatus('product_type_list','admin'))

                                        <li>
                                            <a href="{{route('category.index')}}">
                                                <span>@lang('backoffice.category')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $per::chekStatus('brand_list','admin'))
                                        <li>
                                            <a href="{{route('brands.index')}}">
                                                <span>@lang('backoffice.brand')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $per::chekStatus('product_list','admin'))
                                        <li>
                                            <a href="{{route('product.index')}}">
                                                <span>@lang('backoffice.products')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $per::chekStatus('supplier_list','admin'))
                                        <li>
                                            <a href="{{route('supplier.index')}}">
                                                <span>@lang('backoffice.supplier')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $per::chekStatus('addons_list','admin'))
                                        <li>
                                            <a href="{{url('addon')}}">
                                                {{--                                    <a href="{{route('addon.index')}}">--}}
                                                <span>@lang('backoffice.add_addon')</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        </li>
                        @if($per::chekStatus('customer_list') != false || $per::chekStatus('customer_group_list') != false)
                            <li>
                                <a href class="auto" title="Customers">
                                    <i class="icon-people"></i>
                                    <span>@lang('backoffice.customer')</span>
                                </a>
                                <ul class="nav nav-sub dk">
                                    @if( $per::chekStatus('customer_list','admin'))
                                        <li>
                                            <a href="{{route('customer.index')}}">
                                                <span>@lang('backoffice.customer')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if( $per::chekStatus('customer_group_list','admin'))
                                        <li>
                                            <a href="{{route('customergroup.index')}}">
                                                <span>@lang('backoffice.customer_group')</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    <!-- <li>
                            <a href class="auto" title="Customers">
                                <i class="icon-trash"></i>
                                <span>Subscription</span>
                            </a>
                        </li> -->
                            @if($per::chekStatus('user_list') != false || $per::chekStatus('roles_list') != false)
                        <li>
                            <a href class="auto" title="Users">
                                <i class="icon-user-following"></i>
                                <span>@lang('backoffice.user_management')</span>
                            </a>
                            <ul class="nav nav-sub dk">
                                @if( $per::chekStatus('user_list','admin'))

                                    <li>
                                        <a href="{{route('users.index')}}">
                                            <span>@lang('backoffice.user')</span>
                                        </a>
                                    </li>
                                @endif
                                @if( $per::chekStatus('roles_list','admin'))

                                    <li>
                                        <a href="{{route('roles.index')}}">
                                            <span>@lang('backoffice.role_permissions')</span>
                                        </a>
                                    </li>
                                @endif

                                {{--								<li>--}}
                                {{--									<a href="{{route('permission.index')}}">--}}
                                {{--										<span>Permission</span>--}}
                                {{--									</a>--}}
                                {{--								</li>--}}
                            </ul>
                        </li>
                            @endif
                            @if($per::chekStatus('apps_view') != false )
                        <li>
                            <a href="" class="auto" title="Apps">
                                <i class="icon-layers"></i>
                                <span>@lang('backoffice.apps')</span>
                            </a>
                            <ul class="nav nav-sub dk">
                                <li>
                                    <a href="{{route('apps.index')}}">
                                        <span>@lang('backoffice.your_apps')</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('app-store')}}">
                                        <span>@lang('backoffice.app_store')</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                            @endif
                            @if($per::chekStatus('plugin_view') != false )
                        <li>
                            <a href="" class="auto" title="Plugins">
                                <i class="icon-puzzle"></i>
                                <span>Plugins</span>
                            </a>
                            <ul class="nav nav-sub dk">
                                <li>
                                    <a href="{{route('plugins.index')}}">
                                        <span>Your Plugins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('plugin-store')}}">
                                        <span>Plugin Store</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                            @endif
                        <!-- <li>
                            <a href="#" class="auto" title="Reporting">
                                <i class="icon-notebook"></i>
                                <span>Reporting </span>
                            </a>
                            <ul class="nav nav-sub dk">
                                <li>
                                    <a href="products.html">
                                        <span>Filters</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="supplers.html">
                                        <span>Important Basic</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="inventry.html">
                                        <span>Advance Reports</span>
                                    </a>
                                </li>
                        </li> -->


                    </ul>
                </nav>
                <!-- nav -->
                <!-- aside footer -->
                <!-- / aside footer -->
            </div>
        </div>








        <div class="bottomMenu">
            <ul>
                <li class="">
{{--                    @dd($per::chekStatus('ecommerce_setup_edit','admin'))--}}
                    @if( $per::chekStatus('general_setup_edit','admin') || $per::chekStatus('outlet_list','admin'))
                    <a href="{{ route('outlets.index') }}" aria-haspopup="true" aria-expanded="false" title="Setup">
                        <i class="icon-settings color2"></i>
                        <span>Setup</span>
                    </a>
                    @endif
                    <!-- <div class="dropdown-menu">
                        <ul>
                            <li><a href="">Languages</a></li>
                            <li><a href="">Settings</a></li>
                            <li><a href="">Settings</a></li>
                        </ul>
                    </div> -->
                </li>
                <!-- <li>
                    <a href class="auto" title="Notification">
                        <i class="icon-screen-desktop color2"></i>
                        <span>Notification</span>
                    </a>
                </li> -->
                <li>
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Profile">
                        <i class="icon-user color1"></i>
                        <span>Profile</span>
                    </a>
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="{{route('profile_index')}}">My Account</a></li>
                            <li><a href="javascript:;" onclick="$('#logout-form').submit()">Logout</a></li>
                        </ul>
                    </div>
                </li>

        </div>
    </aside>
    <!-- / aside -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $(".menu-toogler").click(function () {
            $(this).addClass("active");
        });
    });


</script>

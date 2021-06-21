<div class="left_side_menu_1">


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


                        @can('view-dashboard')
                            <li>
                                <a href="{{ route('backoffice.dashboard') }}" class="auto" title="Dashboard"><i
                                        class="icon-grid"></i> <span>@lang('backoffice.dashboard')</span>
                                </a>

                            <!-- <a href="{{url('backoffice/dashboard')}}"></a> -->

                            </li>
                        @endcan


                            @canany(["product-report","category-report","supplier-report","brand-report","user-report","reporting_sales-report","reporting_inventory-report","register_closure-report",
"customer-report","customer_group-report","addon-report","product-export","category-export","supplier-export","brand-export","user-export","reporting_sales-export",
"addon-export"])
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
                        @endcanany




                        @canany(["import-import catalogue" ,"list-category" , "list-brand" , "list-supplier" , "list-addons" , "list-product" , "list-addons"])
                            <li>
                                <a href class="auto" title="Catalogue Management">
                                    <i class="icon-notebook"></i>
                                    <span>@lang('backoffice.catelouge_management')</span>
                                </a>
                                <ul class="nav nav-sub dk">

                                    @can('import-import catalogue')
                                        <li>
                                            <a href="{{ route('import.products')}}">
                                                <span>{{ __('backoffice.import_catalogue') }}</span>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('list-category')

                                        <li>
                                            <a href="{{route('category.index')}}">
                                                <span>@lang('backoffice.category')</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('list-brand')

                                        <li>
                                            <a href="{{route('brands.index')}}">
                                                <span>@lang('backoffice.brand')</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('list-product')
                                        <li>
                                            <a href="{{route('product.index')}}">
                                                <span>@lang('backoffice.products')</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('list-supplier')
                                        <li>
                                            <a href="{{route('supplier.index')}}">
                                                <span>@lang('backoffice.supplier')</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('list-addons')
                                        <li>
                                            <a href="{{route('addon.index')}}">
                                                {{--                                    <a href="{{route('addon.index')}}">--}}
                                                <span>@lang('backoffice.add_addon')</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['list-customer','list-customergroup'])
                            <li>
                                <a href class="auto" title="Customers">
                                    <i class="icon-people"></i>
                                    <span>@lang('backoffice.customer')</span>
                                </a>
                                <ul class="nav nav-sub dk">
                                    @can('list-customer')
                                        <li>
                                            <a href="{{route('customer.index')}}">
                                                <span>@lang('backoffice.customer')</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('list-customergroup')
                                        <li>
                                            <a href="{{route('customergroup.index')}}">
                                                <span>@lang('backoffice.customer_group')</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['list-user','list-role','list-permission'])
                            <li>
                                <a href class="auto" title="Users">
                                    <i class="icon-user-following"></i>
                                    <span>@lang('backoffice.user_management')</span>
                                </a>
                                <ul class="nav nav-sub dk">
                                    {{--                                @if( $per::chekStatus('user_list','admin'))--}}

                                    <li>
                                        <a href="{{route('users.index')}}">
                                            <span>@lang('backoffice.user')</span>
                                        </a>
                                    </li>
                                    {{--                                @endif--}}
                                    {{--                                @if( $per::chekStatus('roles_list','admin'))--}}

                                    <li>
                                        <a href="{{route('roles.index')}}">
                                            <span>@lang('backoffice.role_permissions')</span>
                                        </a>
                                    </li>
                                    {{--                                @endif--}}

                                    <li>
                                        <a href="{{route('permission.index')}}">
                                            <span>Permission</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        @can('list-app')
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
                                        <a href="{{route('app-store')}}">
                                            <span>@lang('backoffice.app_store')</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('list-plugin')
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
                        @endcan
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

                        @canany(['edit-store','list-outlet'])
                        <a href="{{ route('outlets.index') }}" aria-haspopup="true" aria-expanded="false" title="Setup">
                            <i class="icon-settings color2"></i>
                            <span>Setup</span>
                        </a>
                            @endcanany

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

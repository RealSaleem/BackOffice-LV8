<div id="aside1" class="p-3 submenu hidden-xs">
    <a href="#" class="menu-toogler1" id="fold-sub-menu">
        <i class="icon-menu text"></i>
        <i class="icon-menu text-active"></i>
    </a>
    <div class="dropdown show" id="p-menu">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-handbag"></i><span> {{__('submenu.ecommerce')}}</span> </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="icon-handbag color1"></i><span> {{__('submenu.ecommerce')}}</span></a>
            <a class="dropdown-item" href="#"><i class="icon-credit-card color2"></i><span> {{__('submenu.pos')}}</span></a>
            <a class="dropdown-item" href="#"> <i class="icon-screen-desktop color3"></i><span> {{__('submenu.display')}}</span></a>
        </div>
    </div>
    <ul class="nav nav-stacked" id="accordion1">
        <li>
            <a href="{{ route('site.dashboard') }}"><i class="icon-speedometer"></i><span> {{__('submenu.dashboard')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.banner') }}"><i class="fa fa-image"></i><span> {{__('submenu.banner')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.pages') }}"><i class="fa fa-file-o"></i><span> {{__('submenu.page')}}</span></a>
        </li>
        <li>
            <a href="{{ route('email.index') }}"><i class="fa fa-envelope"></i><span> {{__('submenu.email_template')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.contact') }}"><i class="fa fa-envelope"></i><span> {{__('submenu.contact_us')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.order.management') }}"><i class="icon-basket"></i><span> {{__('submenu.order')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.products.web') }}"><i class="icon-social-dropbox"></i><span> {{__('submenu.product')}}</span></a>
        </li>

        <li>
            <a href="{{ route('site.category.web') }}"><i class="fa fa-th"></i><span> {{__('submenu.category')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.customers') }}"><i class="icon-people"></i><span> {{__('submenu.customer')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.newsletters') }}"><i class="icon-paper-plane"></i><span> {{__('submenu.subscription')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.themes') }}"><i class="fa fa-desktop"></i><span> {{__('submenu.theme')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.payments') }}"><i class="icon-credit-card"></i><span> {{__('submenu.pay_gate')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.shippings') }}"><i class="fa fa-truck"></i><span> {{__('submenu.ship_meth')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.settings') }}"><i class="icon-settings"></i><span> {{__('submenu.setting')}}</span></a>
        </li>
        <li>
            <a href="{{ route('site.setup.summary') }}"><i class="fa fa-laptop"></i><span>{{__('submenu.ecommerce_setup')}}</span> </a>
        </li>
    </ul>
</div>

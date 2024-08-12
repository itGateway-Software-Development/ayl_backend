<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <img style="width: 70px;" src="{{ asset('logo.png') }}" alt="">
            <span class="demo menu-text fw-bolder ms-2" style="font-size: 20px;">{{ __('messages.panel_name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('admin') ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-dashboard'></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <!-- Layouts -->
        @can('user_management_access')
            <li
                class="menu-item {{ request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/roles') || request()->is('admin/roles/*') || request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                    <div data-i18n="Layouts">User Management</div>
                </a>

                <ul class="menu-sub">
                    @can('permission_access')
                        <li
                            class="menu-item {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active open' : '' }}">
                            <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                                <div data-i18n="Without menu">Permission</div>
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li
                            class="menu-item {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active open' : '' }}">
                            <a href="{{ route('admin.roles.index') }}" class="menu-link">
                                <div data-i18n="Without menu">Roles</div>
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li
                            class="menu-item {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active open' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class="menu-link">
                                <div data-i18n="Without menu">Users</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>

        @can('point_system_access')
            <li class="menu-item {{ request()->is('admin/point_system/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon bx bxs-gift'></i>
                    <div data-i18n="Point Settings">Point System</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('admin/point_system/points') || request()->is('admin/point_system/points/*') ? 'active open' : '' }}">
                        <a href="{{route('admin.points.index')}}" class="menu-link">
                            <div data-i18n="Points">Points</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('product_setting_access')
            <li class="menu-item {{ request()->is('admin/product_setting/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bx-package'></i>
                    <div data-i18n="Product Settings">Product Settings</div>
                </a>
                <ul class="menu-sub">
                    @can('category_access')
                        <li class="menu-item {{ request()->is('admin/product_setting/categories') || request()->is('admin/product_setting/categories/*') ? 'active open' : '' }}">
                            <a href="{{route('admin.categories.index')}}" class="menu-link">
                                <div data-i18n="Categories">Categories</div>
                            </a>
                        </li>
                    @endcan
                    @can('series_access')
                        <li class="menu-item {{ request()->is('admin/product_setting/series') || request()->is('admin/product_setting/series/*') ? 'active open' : '' }}">
                            <a href="{{route('admin.series.index')}}" class="menu-link">
                                <div data-i18n="Series">Series</div>
                            </a>
                        </li>
                    @endcan
                    @can('product_access')
                        <li class="menu-item {{ request()->is('admin/product_setting/products') || request()->is('admin/product_setting/products/*') ? 'active open' : '' }}">
                            <a href="{{route('admin.products.index')}}" class="menu-link">
                                <div data-i18n="Products">Products</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('order_setting_access')
            <li class="menu-item {{ request()->is('admin/order_setting/*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-truck'></i>
                    <div data-i18n="Product Settings">Orders Setting</div>
                </a>
                <ul class="menu-sub">
                    @can('order_access')
                        <li class="menu-item {{ request()->is('admin/order_setting/orders') || request()->is('admin/order_setting/orders/*') ? 'active open' : '' }}">
                            <a href="{{route('admin.orders.index')}}" class="menu-link">
                                <div data-i18n="Orders">Orders</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
    </ul>
</aside>

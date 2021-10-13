<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{url('/admin/dashboard')}}" class="navbar-brand"><img src="{{asset('img/logo.png')}}"></a>
    </div>
        <?php
            $id = Auth::user()->id;   
            $menu_read = menuPermissionByType($id,"read");
            // dd($menu_read);
        ?>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item @yield('dashboard_select')">
                <a href="{{url('/admin/dashboard')}}" class="nav-link">
                    <i class="link-icon dashboard-icon" data-feather=""></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @if(in_array("admin-users",$menu_read))
            <li class="nav-item @yield('admin_users_select')">
                <a href="{{url('admin/admin-management')}}" class="nav-link">
                    <i class="link-icon side-user-icon" data-feather=""></i>
                    <span class="link-title">Manage Admin Users</span>
                </a>
            </li>
            @endif
            @if(in_array("portal-users",$menu_read))
            <li class="nav-item @yield('manage_portal_users_select')">
                <a href="{{route('user-management.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Manage Portal Users</span>
                </a>
            </li>
            @endif
            <!-- @if(in_array("transaction-history",$menu_read))
            <li class="nav-item @yield('transaction_history_select')">
                <a href="{{route('transaction-history.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Transaction History</span>
                </a>
            </li>
            @endif -->
            @if(in_array("probs-category",$menu_read))
            <li class="nav-item @yield('probs_category_select')">
                <a href="{{url('admin/probs-category')}}" class="nav-link">
                    <i class="link-icon deepweb-tool-box-icon" data-feather=""></i>
                    <span class="link-title">Category</span>
                </a>
            </li>
            @endif
            @if(in_array("probs-sub-category",$menu_read))
            <li class="nav-item @yield('probs_sub_category_select')">
                <a href="{{url('admin/probs-sub-category')}}" class="nav-link">
                    <i class="link-icon deepweb-tool-box-icon" data-feather=""></i>
                    <span class="link-title">Sub Category</span>
                </a>
            </li>
            @endif
            @if(in_array("email-management",$menu_read))
            <li class="nav-item @yield('email_management_select')">
                <a href="{{route('email-management.index')}}" class="nav-link">
                    <i class="link-icon side-email-icon" data-feather=""></i>
                    <span class="link-title">Email Management</span>
                </a>
            </li>
            @endif
            @if(in_array("content-management",$menu_read))
            <li class="nav-item @yield('content_management_select')">
                <a href="{{route('content-management.index')}}" class="nav-link">
                    <i class="link-icon side-content-icon" data-feather=""></i>
                    <span class="link-title">Content Management</span>
                </a>
            </li>
            @endif
            <!-- @if(in_array("dynamic-content",$menu_read))
            <li class="nav-item @yield('dynamic_content_select')">
                <a href="{{url('admin/dynamic-content')}}" class="nav-link">
                    <i class="link-icon side-dynamic-icon" data-feather=""></i>
                    <span class="link-title">Dynamic Content</span>
                </a>
            </li>
            @endif -->
            @if(in_array("banner-management",$menu_read))
            <li class="nav-item @yield('banner_management_select')">
                <a href="{{url('admin/banner-management')}}" class="nav-link">
                     <i class="link-icon side-banner-icon" data-feather=""></i>
                    <span class="link-title">Banner Management</span>
                </a>
            </li>
            @endif
            @if(in_array("faq",$menu_read))
            <li class="nav-item @yield('faq_select')">
                <a href="{{url('admin/faq')}}" class="nav-link">
                    <i class="link-icon side-faq-icon" data-feather=""></i>
                    <span class="link-title">FAQ</span>
                </a>
            </li>
            @endif
            @if(in_array("general-settings",$menu_read))
            <li class="nav-item @yield('setting_select')">
                <a href="{{url('admin/settings')}}" class="nav-link">
                    <i class="link-icon side-setting-icon" data-feather=""></i>
                    <span class="link-title">General Settings</span>
                </a>
            </li>
            @endif
            @if(in_array("promotion",$menu_read))
             <li class="nav-item @yield('promotion_select')">
                <a href="{{route('promotion.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Promotions</span>
                </a>
            </li>
            @endif
            @if(in_array("product",$menu_read))
            <li class="nav-item @yield('product_select')">
                <a href="{{route('product.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Product</span>
                </a>
            </li>
            @endif
            @if(in_array("variant",$menu_read))
            <li class="nav-item @yield('variant_select')">
                <a href="{{route('variant.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Variant</span>
                </a>
            </li>
            @endif
            @if(in_array("ingredient",$menu_read))
            <li class="nav-item @yield('ingredient_select')">
                <a href="{{route('ingredient.index')}}" class="nav-link">
                    <i class="link-icon side-portal-icon" data-feather=""></i>
                    <span class="link-title">Ingredient</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>














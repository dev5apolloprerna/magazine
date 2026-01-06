    <?php 
if(auth()->user())
{
$roleid = auth()->user()->role_id;
}else{

$roleid = Auth::guard('web_employees')->user()->role_id;
}
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                 <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                        href="{{ route('home') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                @if($roleid == '1' && $roleid != '2')
                   
                    <!-- Category -->
                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('magazine.*')) active @endif"
                            href="{{ route('magazine.index') }}">
                            <i class="fas fa-folder"></i>
                            <span data-key="t-category">Magazines</span>
                        </a>
                    </li>
                      <li class="nav-item">
                        <a href="{{ route('plan.index') }}" class="nav-link {{ request()->is('admin/plan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cube"></i>
                                Plan
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('customer.index') }}" class="nav-link {{ request()->is('admin/customer*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>Customer
                    </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.subscriptions') }}" class="nav-link {{ request()->is('admin/customer-subscriptions*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>Customer Subscriptions
                        </a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i> Reports </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.customers.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}">
                                      <i class="far fa-circle nav-icon"></i></i>Customer Login Report
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.userWisePdfViews') }}"
                                   class="nav-link {{ request()->routeIs('admin.reports.userWisePdfViews*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-pdf"></i>
                                    Customer Magazine View Report
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.magazineWisePdfViews') }}"
                                   class="nav-link {{ request()->routeIs('admin.reports.magazineWisePdfViews*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>Magazine View Report</p>
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>


                 
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
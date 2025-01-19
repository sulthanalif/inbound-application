<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard*') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        @role('Super Admin')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('logs-activity') ? '' : 'collapsed' }}" href="{{ route('logs.index') }}">
                <i class="bi bi-clock-history"></i>
                <span>Logs Activity</span>
            </a>
        </li><!-- End Dashboard Nav -->
        @endrole



        @hasrole('Super Admin|Admin Warehouse|Head Warehouse')
            <li class="nav-heading">Master</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('goods*', 'problem-items*', 'report/goods') ? '' : 'collapsed' }}"
                    data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-box-seam"></i><span>Goods</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav"
                    class="nav-content collapse {{ request()->is('goods*', 'problem-items*', 'report/goods') ? 'show' : '' }}"
                    data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('goods.index') }}" class="{{ request()->is('goods*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Items</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('problems.index') }}"
                            class="{{ request()->is('problem-items*') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Problem Items</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('goods.reportGoods')}}"
                            class="{{ request()->is('report/goods') ? 'active' : '' }}">
                            <i class="bi bi-circle"></i><span>Report</span>
                        </a>
                    </li>

                </ul>
            </li>

            {{-- <li class="nav-item">
        <a class="nav-link {{ request()->is('goods*') ? '' : 'collapsed' }}" href="{{ route('goods.index') }}">
          <i class="bi bi-box-seam"></i>
          <span>Goods</span>
        </a>
      </li> --}}

            <li class="nav-item">
                <a class="nav-link {{ request()->is('categories*') ? '' : 'collapsed' }}"
                    href="{{ route('categories.index') }}">
                    <i class="bi bi-boxes"></i>
                    <span>Categories</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('units*') ? '' : 'collapsed' }}" href="{{ route('units.index') }}">
                    <i class="bi bi-bookmarks"></i>
                    <span>Units</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('warehouses*') ? '' : 'collapsed' }}"
                    href="{{ route('warehouses.index') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Werehouses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('vendors*') ? '' : 'collapsed' }}"
                    href="{{ route('vendors.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Vendors</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('delivery-areas*') ? '' : 'collapsed' }}"
                    href="{{ route('delivery-areas.index') }}">
                    <i class="bi bi-building"></i>
                    <span>Delivery Area</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? '' : 'collapsed' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Projects</span>
                </a>
            </li>
        @endhasrole

        @hasrole('Admin Engineer')
            <li class="nav-heading">Master</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? '' : 'collapsed' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Projects</span>
                </a>
            </li>
        @endhasrole

        @hasrole('Super Admin')
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->is('projects*') ? '' : 'collapsed' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-cone-striped"></i>
                    <span>Projects</span>
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('roles*') ? '' : 'collapsed' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Roles</span>
                </a>
            </li>
        @endhasrole

        <li class="nav-heading">Transaction</li>

        @hasrole('Super Admin|Admin Warehouse|Head Warehouse|Admin Engineer')
            <li class="nav-item">
                <a class="nav-link {{ request()->is('outbounds*') ? '' : 'collapsed' }}"
                    href="{{ route('outbounds.index') }}">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Outbound</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('inbounds*') ? '' : 'collapsed' }}"
                    href="{{ route('inbounds.index') }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Inbound</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link {{ request()->is('inbounds*') ? '' : 'collapsed' }}"
                    href="{{ route('inbounds.index') }}">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Resend</span>
                </a>
            </li><!-- End Register Page Nav -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('inbounds*') ? '' : 'collapsed' }}"
                    href="{{ route('inbounds.index') }}">
                    <i class="bi bi-exclamation-diamond"></i>
                    <span>Problem</span>
                </a>
            </li><!-- End Register Page Nav --> --}}
        @endhasrole

        {{-- <li class="nav-heading">Report</li> --}}
        {{-- @role('Head Warehouse')
            <li class="nav-item">
                <a class="nav-link"
                    href="#">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Inbound</span>
                </a>
            </li>
        @endrole --}}
    </ul>

</aside><!-- End Sidebar-->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard*') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->



      <li class="nav-heading">Master</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('goods*') ? '' : 'collapsed' }}" href="{{ route('goods.index') }}">
          <i class="bi bi-box-seam"></i>
          <span>Goods</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('categories*') ? '' : 'collapsed' }}" href="{{ route('categories.index') }}">
          <i class="bi bi-boxes"></i>
          <span>Categories</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('warehouses*') ? '' : 'collapsed' }}" href="{{ route('warehouses.index') }}">
          <i class="bi bi-house-door"></i>
          <span>Werehouses</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('vendors*') ? '' : 'collapsed' }}" href="{{ route('vendors.index') }}">
          <i class="bi bi-people"></i>
          <span>Vendors</span>
        </a>
      </li>

      @hasrole('Super Admin')
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

      @hasrole('Super Admin|Admin Engineer')
      <li class="nav-item">
        <a class="nav-link {{ request()->is('request-goods*') ? '' : 'collapsed' }}" href="{{ route('request-goods.index') }}">
            <i class="bi bi-cart"></i>
          <span>Request Goods</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('returns*') ? '' : 'collapsed' }}" href="{{ route('returns.index') }}">
            <i class="bi bi-cart"></i>
          <span>Return Goods</span>
        </a>
      </li>
      @endhasrole

      @hasrole('Super Admin|Admin Warehouse|Head Warehouse|Admin Engineer')
      <li class="nav-item">
        <a class="nav-link {{ request()->is('outbounds*') ? '' : 'collapsed' }}" href="{{ route('outbounds.index') }}">
            <i class="bi bi-box-arrow-left"></i>
          <span>Outbound</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="">
            <i class="bi bi-box-arrow-in-right"></i>
          <span>Inbound</span>
        </a>
      </li><!-- End Register Page Nav -->
      @endhasrole

    </ul>

  </aside><!-- End Sidebar-->

            <div class="sidebar">
    <div class="sidebar-header">
        <h2>Inventory System</h2>
    </div>
    <div class="sidebar-menu">
        <div class="menu-items">
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.items.index') }}" class="menu-item">
                <i class="fas fa-box"></i>
                Inventory
            </a>
            <a href="{{ route('admin.departments.index') }}" class="menu-item {{ request()->is('admin/departments*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Departments
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-clipboard-list"></i>
                Requests
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                Reports
            </a>
            <a href="{{ route('admin.staff.index') }}" class="menu-item">
                <i class="fas fa-user-tie"></i>
                Staff Management
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                Settings
            </a>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="logout-container">
            @csrf
            <button type="submit" class="logout-btn menu-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>
</div>
<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
</head>

<aside class="sidebar" id="sidebar">

    <nav class="sidebar-nav">
        
        <ul>
            @if(hasPermission('dashboard.view'))
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="ti ti-layout-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            @endif

            @if(hasPermission('members.view'))
            <li class="nav-item {{ request()->routeIs('users') ? 'active' : '' }}">
                <a href="{{ route('users') }}" class="nav-link">
                    <i class="ti ti-users"></i>
                    <span class="nav-text">User Management</span>
                </a>
            </li>
            @endif

            @if(hasPermission('hr.view'))
            <li class="nav-item {{ request()->routeIs('hr-management') ? 'active' : '' }}">
                <a href="{{ route('hr-management') }}" class="nav-link">
                    <i class="ti ti-briefcase"></i>
                    <span class="nav-text">HR Management</span>
                </a>
            </li>
            @endif

            @if(hasPermission('permissions.view'))
            <li class="nav-item {{ request()->routeIs('roles') ? 'active' : '' }}">
                <a href="{{ route('roles') }}" class="nav-link">
                    <i class="ti ti-shield-lock"></i>
                    <span class="nav-text">Role & Permission</span>
                </a>
            </li>
            @endif

             @if(hasPermission('attendance.view'))
            <li class="nav-item {{ request()->routeIs('attendance') ? 'active' : '' }}">
                <a href="{{ route('attendance') }}" class="nav-link">
                    <i class="ti ti-clock"></i>
                    <span class="nav-text">Attendance</span>
                </a>
            </li>
            @endif

            @if(hasPermission('leaves.view'))
            <li class="nav-item {{ request()->routeIs('leave') ? 'active' : '' }}">
                <a href="{{ route('leave') }}" class="nav-link">
                    <i class="ti ti-calendar-event"></i>
                    <span class="nav-text">Leave Management</span>
                </a>
            </li>
            @endif

            @if(hasPermission('projects.view'))
            <li class="nav-item {{ request()->routeIs('projects') ? 'active' : '' }}">
                <a href="{{ route('projects') }}" class="nav-link">
                    <i class="ti ti-folders"></i>
                    <span class="nav-text">Projects</span>
                </a>
            </li>
            @endif

            @if(hasPermission('tasks.view'))
            <li class="nav-item {{ request()->routeIs('tasks') ? 'active' : '' }}">
                <a href="{{ route('tasks') }}" class="nav-link">
                    <i class="ti ti-checklist"></i>
                    <span class="nav-text">Tasks</span>
                </a>
            </li>
            @endif

            @if(hasPermission('dwr.view'))
            <li class="nav-item {{ request()->routeIs('daily-work-report') ? 'active' : '' }}">
                <a href="{{ route('daily-work-report') }}" class="nav-link">
                    <i class="ti ti-clipboard"></i>
                    <span class="nav-text">Daily Work Report</span>
                </a>
            </li>
            @endif

            @if(hasPermission('reports.view'))
            <li class="nav-item {{ request()->routeIs('reports') ? 'active' : '' }}">
                <a href="{{ route('reports') }}" class="nav-link">
                    <i class="ti ti-chart-bar"></i>
                    <span class="nav-text">Reports</span>
                </a>
            </li>
            @endif

            @if(hasPermission('payroll.view'))
            <li class="nav-item {{ request()->routeIs('payroll') ? 'active' : '' }}">
                <a href="{{ route('payroll') }}" class="nav-link">
                    <i class="ti ti-cash"></i>
                    <span class="nav-text">Payroll</span>
                </a>
            </li>
            @endif

            @if(hasPermission('settings.view'))
            <li class="nav-item {{ request()->routeIs('system-settings') ? 'active' : '' }}">
                <a href="{{ route('system-settings') }}" class="nav-link">
                    <i class="ti ti-settings"></i>
                    <span class="nav-text">System Settings</span>
                </a>
            </li>
            @endif

            @if(hasPermission('audit.view'))
            <li class="nav-item {{ request()->routeIs('audit') ? 'active' : '' }}">
                <a href="{{ route('audit') }}" class="nav-link">
                    <i class="ti ti-history"></i>
                    <span class="nav-text">Audit Logs</span>
                </a>
            </li>
            @endif

        </ul>
    </nav>
</aside>
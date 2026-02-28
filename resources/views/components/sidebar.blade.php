<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>


<aside class="sidebar" id="sidebar">
    <!-- <div class="sidebar-header">
        <div class="logo">
            <i class="ti ti-brand-gitlab"></i>
            <span class="logo-text">AdminPro</span>
        </div>
    </div> -->

    <nav class="sidebar-nav">
        <ul>

            @php
            $role = session('member_role');

            $menus = [
            1 => [ // Super Admin
            ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'title' => 'Dashboard'],
            ['route' => 'users', 'icon' => 'ti-users', 'title' => 'User Management'],
            ['route' => 'roles', 'icon' => 'ti-shield-lock', 'title' => 'Role & Permission'],
            ['route' => 'projects', 'icon' => 'ti-folders', 'title' => 'Projects'],
            ['route' => 'hr-management', 'icon' => 'ti-briefcase', 'title' => 'HR Management'],
            ['route' => 'tasks', 'icon' => 'ti-checklist', 'title' => 'Tasks'],
            ['route' => 'reports', 'icon' => 'ti-chart-bar', 'title' => 'Reports'],
            ['route' => 'attendance', 'icon' => 'ti-clock', 'title' => 'Attendance'],
            ['route' => 'leave', 'icon' => 'ti-calendar-event', 'title' => 'Leave Management'],
            ['route' => 'payroll', 'icon' => 'ti-cash', 'title' => 'Payroll'],
            ['route' => 'system-settings', 'icon' => 'ti-settings', 'title' => 'System Settings'],
            ['route' => 'audit', 'icon' => 'ti-history', 'title' => 'Audit Logs'],
            ],

            2 => [ // Admin
            ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'title' => 'Dashboard'],
            ['route' => 'users', 'icon' => 'ti-users', 'title' => 'User Management'],
            ['route' => 'projects', 'icon' => 'ti-folders', 'title' => 'Projects'],
            ['route' => 'reports', 'icon' => 'ti-chart-bar', 'title' => 'Reports'],
            ['route' => 'attendance', 'icon' => 'ti-clock', 'title' => 'Attendance'],
            ['route' => 'leave', 'icon' => 'ti-calendar-event', 'title' => 'Leave Management'],
            ],

            3 => [ // HR
            ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'title' => 'Dashboard'],
            ['route' => 'hr-management', 'icon' => 'ti-briefcase', 'title' => 'HR Management'],
            ['route' => 'attendance', 'icon' => 'ti-clock', 'title' => 'Attendance'],
            ['route' => 'leave', 'icon' => 'ti-calendar-event', 'title' => 'Leave Management'],
            ['route' => 'payroll', 'icon' => 'ti-cash', 'title' => 'Payroll'],
            ],

            4 => [ // Manager
            ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'title' => 'Dashboard'],
            ['route' => 'projects', 'icon' => 'ti-folders', 'title' => 'Projects'],
            ['route' => 'tasks', 'icon' => 'ti-checklist', 'title' => 'Tasks'],
            ['route' => 'reports', 'icon' => 'ti-chart-bar', 'title' => 'Reports'],
            ['route' => 'attendance', 'icon' => 'ti-clock', 'title' => 'Attendance'],
            ['route' => 'leave', 'icon' => 'ti-calendar-event', 'title' => 'Leave Management'],
            ],

            5 => [ // Employee
            ['route' => 'dashboard', 'icon' => 'ti-layout-dashboard', 'title' => 'Dashboard'],
            ['route' => 'tasks', 'icon' => 'ti-checklist', 'title' => 'My Tasks'],
            ['route' => 'attendance', 'icon' => 'ti-clock', 'title' => 'Attendance'],
            ['route' => 'leave', 'icon' => 'ti-calendar-event', 'title' => 'Leave Management'],
            ],
            ];

            $navItems = $menus[$role] ?? [];
            @endphp


            @foreach($navItems as $item)
            <li class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <a href="{{ route($item['route']) }}" class="nav-link">
                    <i class="ti {{ $item['icon'] }}"></i>
                    <span class="nav-text">{{ $item['title'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </nav>
</aside>
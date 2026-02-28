@php
    use Illuminate\Support\Facades\DB;

    $member = null;

    if(session()->has('member_id')) {
        $member = DB::table('members')
            ->leftJoin('roles', 'members.role_id', '=', 'roles.id')
            ->select('members.*', 'roles.role_name')
            ->where('members.id', session('member_id'))
            ->first();
    }
@endphp

<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />

</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<header class="topbar">

    <div class="topbar-left">
        <button class="menu-toggle-btn" id="sidebar-toggle">
            <i class="ti ti-menu-2"></i>
        </button>

        <div class="logo">
            <img src="{{ asset('img/ES_Logo.png') }}" alt="Logo">
        </div>

        <div class="search-bar">
            <i class="ti ti-search"></i>
            <input type="text" placeholder="Search dashboard...">
        </div>
    </div>

    <div class="topbar-right">
        <button class="icon-btn" id="theme-toggle">
            <i class="ti ti-moon"></i>
        </button>

        <button class="icon-btn">
            <i class="ti ti-bell"></i>
            <span class="badge">3</span>
        </button>

        <div class="user-dropdown">
            <button class="user-menu-btn">
                @if($member && $member->profile_photo)
                <img src="{{ asset('storage/profile/'.$member->profile_photo) }}" class="header-avatar">
                @else
                <img src="{{ asset('img/Profile.png') }}" class="header-avatar">
                @endif
                <i class="ti ti-chevron-down"></i>
            </button>

            <div class="dropdown-menu">
                <a href="{{ url('profile') }}" class="dropdown-item">
                    <i class="ti ti-user"></i> My Profile
                </a>

                <a href="{{ url('settings') }}" class="dropdown-item">
                    <i class="ti ti-settings"></i> Settings
                </a>

                <div class="dropdown-divider"></div>


                <a href="{{ route('logout') }}" class="dropdown-item logout">
                    <i class="ti ti-logout"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script src="{{ asset('js/main.js') }}" defer></script>

@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Added for the Str::limit helper

$member = null;
$notifications = [];

if(session()->has('member_id')) {
    $userId = session('member_id');
    
    $member = DB::table('members')
        ->leftJoin('roles', 'members.role_id', '=', 'roles.id')
        ->select('members.*', 'roles.role_name')
        ->where('members.id', $userId)
        ->first();

    // GET PERSONALIZED NOTIFICATIONS
    // We filter by member_id to show only things that concern THIS user
    $notifications = DB::table('audit_logs')
        ->where('member_id', $userId) // <--- CRITICAL FILTER
        ->orderBy('created_at', 'desc')
        ->limit(5) // Increased to 5 for a better UX
        ->get();
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

        <div class="search-bar" style="position: relative;">
    <i class="ti ti-search"></i>
    <input type="text" id="global-search-input" placeholder="Search members or months..." autocomplete="off">

    <div id="search-results" class="global-search-results" 
         style="display:none; width: 100%; top: 45px; position: absolute; z-index: 99999; background: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; box-shadow: var(--shadow-lg); max-height: 400px; overflow-y: auto;">
    </div>
</div>
    </div>

    <div class="topbar-right">
        <button class="icon-btn" id="theme-toggle">
            <i class="ti ti-moon"></i>
        </button>

        <div class="user-dropdown" style="margin-right: 15px;">
    <button class="icon-btn" id="notif-toggle">
        <i class="ti ti-bell"></i>
        @if(count($notifications) > 0)
            <span class="badge" style="background: #ff4d4d; color: white;">{{ count($notifications) }}</span>
        @endif
    </button>
    
    <div class="dropdown-menu" id="notif-menu" style="width: 300px; padding: 0; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="padding: 15px; font-weight: bold; border-bottom: 1px solid var(--border); background: var(--primary-color); color: white; border-radius: 10px 10px 0 0;">
            Notifications
        </div>

        <div style="max-height: 300px; overflow-y: auto;">
            @forelse($notifications as $note)
                <div class="dropdown-item" style="display: flex; gap: 10px; padding: 12px; border-bottom: 1px solid #f5f5f5; white-space: normal;">
                    {{-- Dynamic Icon based on Action --}}
                    <div style="flex-shrink: 0;">
                        @if(Str::contains(strtolower($note->action), 'leave'))
                            <i class="ti ti-calendar-check" style="color: #28a745; font-size: 20px;"></i>
                        @elseif(Str::contains(strtolower($note->action), 'task'))
                            <i class="ti ti-clipboard-list" style="color: #007bff; font-size: 20px;"></i>
                        @else
                            <i class="ti ti-info-circle" style="color: #6c757d; font-size: 20px;"></i>
                        @endif
                    </div>

                    <div>
                        <div style="font-weight: 600; font-size: 13px; color: var(--text);">
                            {{ $note->action }}
                        </div>
                        <div style="font-size: 12px; color: #666; line-height: 1.3;">
                            {{ Str::limit($note->details ?? $note->activity ?? $note->description, 60) }}
                        </div>
                        <div style="font-size: 10px; color: #999; margin-top: 5px;">
                            <i class="ti ti-clock"></i> {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 30px; text-align: center; color: #999;">
                    <i class="ti ti-bell-off" style="font-size: 30px; display: block; margin-bottom: 10px;"></i>
                    No personal notifications
                </div>
            @endforelse
        </div>
    </div>
</div>

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

                <!-- <a href="{{ url('settings') }}" class="dropdown-item">
                    <i class="ti ti-settings"></i> Settings
                </a> -->

                <div class="dropdown-divider"></div>


                <a href="{{ route('logout') }}" class="dropdown-item logout">
                    <i class="ti ti-logout"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>


<script src="{{ asset('js/main.js') }}" defer></script>
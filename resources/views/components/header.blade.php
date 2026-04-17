@php
use Illuminate\Support\Facades\DB;
$member = null;
$notifications = [];

if(session()->has('member_id')) {
$member = DB::table('members')
->leftJoin('roles', 'members.role_id', '=', 'roles.id')
->select('members.*', 'roles.role_name')
->where('members.id', session('member_id'))
->first();

// GET LATEST 3 NOTIFICATIONS
$notifications = DB::table('audit_logs')
->orderBy('created_at', 'desc')
->limit(3)
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
                <span class="badge">{{ count($notifications) }}</span>
            </button>
            <div class="dropdown-menu" id="notif-menu" style="width: 280px; padding: 0;">
                <div style="padding: 12px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <span>Recent Activity</span>
                    <span style="font-size: 10px; color: var(--primary-color);">Latest 3</span>
                </div>
                @foreach($notifications as $note)
                <div class="dropdown-item" style="white-space: normal; padding: 12px; border-bottom: 1px solid #f5f5f5; line-height: 1.4;">
                    {{-- Display Action/Type --}}
                    <div style="font-weight: 600; font-size: 13px;">
                        {{ $note->action ?? ($note->type ?? 'Activity') }}
                    </div>

                    {{-- Display Details Safely --}}
                    <div style="font-size: 12px; color: #666;">
                        @php
                        // This checks multiple possible column names (details, activity, description)
                        $detailText = $note->details ?? ($note->activity ?? ($note->description ?? 'No details available'));
                        @endphp
                        {{ Str::limit($detailText, 60) }}
                    </div>

                    <div style="font-size: 10px; color: #999; margin-top: 4px;">
                        {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
                    </div>
                </div>
                @endforeach
                @if(count($notifications) == 0)
                <div style="padding: 20px; text-align: center; color: #999;">No new notifications</div>
                @endif
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
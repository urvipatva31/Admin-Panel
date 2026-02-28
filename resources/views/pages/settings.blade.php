@include('components.header')
@include('components.sidebar')

<div class="main-container">

    @if(session('error'))
    <div class="alert alert-danger">
        <span>{{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()">×</button>
    </div>
    @endif


    <!-- PAGE HEADER -->
    <div class="page-header">
        <h1>Settings</h1>
        <p style="color:var(--text-muted); margin-top:4px;">
            Manage your personal preferences and workspace behavior
        </p>
    </div>


    <!-- MAIN SETTINGS -->
    <div class="form-section">

        <form method="POST" action="#">
            @csrf


            <!-- ================= INTERFACE ================= -->
            <div class="section-header">
                <h2>Interface</h2>
            </div>

            <div class="grid-container" style="grid-template-columns: 1fr 1fr; margin-bottom:35px;">

                <div class="form-group">
                    <label>Theme</label>
                    <select name="theme">
                        <option value="light">Light Mode</option>
                        <option value="dark">Dark Mode</option>
                        <option value="system">System Default</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Layout Density</label>
                    <select name="layout_density">
                        <option value="comfortable">Comfortable</option>
                        <option value="compact">Compact</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Default Landing Page</label>
                    <select name="default_page">
                        <option value="dashboard">Dashboard</option>
                        <option value="tasks">Tasks</option>
                        <option value="attendance">Attendance</option>
                        <option value="leave">Leave</option>
                        <option value="payroll">Payroll</option>
                    </select>
                </div>

            </div>


            <!-- ================= COMMUNICATION ================= -->
            <div class="section-header">
                <h2>Communication</h2>
            </div>

            <div style="display:flex; flex-direction:column; gap:18px; margin-bottom:35px;">

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="notify_tasks">
                        Notify me when I am assigned a task
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="notify_leave">
                        Notify me about leave request updates
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="notify_attendance">
                        Attendance reminders
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="notify_payroll">
                        Payroll updates
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="email_notifications">
                        Email notifications
                    </label>
                </div>

            </div>


            <!-- ================= WORKSPACE ================= -->
            <div class="section-header">
                <h2>Workspace</h2>
            </div>

            <div class="grid-container" style="grid-template-columns: 1fr 1fr; margin-bottom:35px;">

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="show_online_status">
                        Show my online status to team
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="allow_mentions">
                        Allow task mentions
                    </label>
                </div>

            </div>


            <!-- ================= SESSION ================= -->
            <div class="section-header">
                <h2>Session & Security</h2>
            </div>

            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Session Timeout</label>
                    <select name="session_timeout">
                        <option value="15">15 Minutes</option>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember_device">
                        Remember this device
                    </label>
                </div>

            </div>


            <div class="form-actions" style="margin-top:45px;">
                <button type="submit" class="btn-primary">
                    Save Changes
                </button>
            </div>

        </form>

    </div>


    <!-- ACCOUNT MANAGEMENT -->
    <div class="form-section">

        <div class="section-header">
            <h2>Account Management</h2>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">

            <div class="form-actions" style="justify-content:flex-start;">
                <button type="button" class="btn-secondary">
                    Download My Data
               
                <button type="button" class="btn-secondary">
                    Logout From All Devices
                </button>
            
                <button type="button" class="btn-secondary text-danger">
                    Deactivate My Account
                </button>
            </div>

        </div>

    </div>

</div>
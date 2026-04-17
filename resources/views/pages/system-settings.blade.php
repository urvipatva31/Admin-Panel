@include('components.header')
@include('components.sidebar')

<div class="main-container">

    <!-- Page Header -->
    <div class="page-header">
        <h1>System Settings</h1>
        <div class="page-actions">
            <button><i class="fas fa-save"></i> Save All Settings</button>
        </div>
    </div>

    <!-- ================= GENERAL SETTINGS ================= -->
    <div class="form-section">
        <h2>General Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" placeholder="Enter company name">
                </div>

                <div class="form-group">
                    <label>Company Logo</label>
                    <input type="file">
                </div>

                <div class="form-group">
                    <label>Favicon</label>
                    <input type="file">
                </div>

                <div class="form-group">
                    <label>Company Address</label>
                    <input type="text" placeholder="Enter address">
                </div>

                <div class="form-group">
                    <label>GST / Tax Number</label>
                    <input type="text" placeholder="Enter GST number">
                </div>

                <div class="form-group">
                    <label>Company Website</label>
                    <input type="text" placeholder="https://example.com">
                </div>

                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" placeholder="admin@example.com">
                </div>

                <div class="form-group">
                    <label>Company Phone</label>
                    <input type="text" placeholder="+91 XXXXX XXXXX">
                </div>

                <div class="form-group">
                    <label>Currency</label>
                    <select>
                        <option>USD ($)</option>
                        <option>INR (₹)</option>
                        <option>EUR (€)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date Format</label>
                    <select>
                        <option>YYYY-MM-DD</option>
                        <option>DD-MM-YYYY</option>
                        <option>MM-DD-YYYY</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Time Format</label>
                    <select>
                        <option>12 Hours</option>
                        <option>24 Hours</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Week Start Day</label>
                    <select>
                        <option>Monday</option>
                        <option>Sunday</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Time Zone</label>
                    <select>
                        <option>UTC</option>
                        <option>Asia/Kolkata</option>
                        <option>America/New_York</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Default Language</label>
                    <select>
                        <option>English</option>
                        <option>Hindi</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update General Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= SECURITY SETTINGS ================= -->
    <div class="form-section">
        <h2>Security Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Password Expiry (Days)</label>
                    <input type="number" value="90">
                </div>

                <div class="form-group">
                    <label>Minimum Password Length</label>
                    <input type="number" value="8">
                </div>

                <div class="form-group">
                    <label>Strong Password Policy</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Two-Factor Authentication</label>
                    <select>
                        <option>Required</option>
                        <option>Optional</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>IP Whitelist</label>
                    <input type="text" placeholder="192.168.1.1, 10.0.0.1">
                </div>

                <div class="form-group">
                    <label>Session Timeout (Minutes)</label>
                    <input type="number" value="30">
                </div>

                <div class="form-group">
                    <label>Max Login Attempts</label>
                    <input type="number" value="5">
                </div>

                <div class="form-group">
                    <label>Login History Tracking</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Security Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= ROLE & PERMISSION ================= -->
    <div class="form-section">
        <h2>Role & Permissions</h2>
        <form>
            <div class="grid-container">

                <div class="form-group">
                    <label>Default Role</label>
                    <select>
                        <option>Admin</option>
                        <option>HR</option>
                        <option>Employee</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Module Permissions</label>
                    <select>
                        <option>Full Access</option>
                        <option>Limited Access</option>
                        <option>Read Only</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Roles</button>
            </div>
        </form>
    </div>


    <!-- ================= ATTENDANCE SETTINGS ================= -->
    <div class="form-section">
        <h2>Attendance Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>GPS-based Attendance</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>IP Restriction</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Overtime Rule (per hour)</label>
                    <input type="number" placeholder="Enter amount">
                </div>

                <div class="form-group">
                    <label>Flexible Shift</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Office Start Time</label>
                    <input type="time">
                </div>

                <div class="form-group">
                    <label>Office End Time</label>
                    <input type="time">
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Attendance Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= LEAVE SETTINGS ================= -->
    <div class="form-section">
        <h2>Leave Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">

                <div class="form-group">
                    <label>Leave Carry Forward</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Leave Encashment</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Holiday Calendar</label>
                    <input type="file">
                </div>

                <div class="form-group">
                    <label>Customize Leave Types</label>
                    <input type="text" placeholder="e.g. Sick, Casual, Earned">
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Leave Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= PAYROLL SETTINGS ================= -->
    <div class="form-section">
        <h2>Payroll Settings</h2>
        <form>
            <div class="grid-container">

                <div class="form-group">
                    <label>Salary Structure</label>
                    <input type="text" placeholder="Basic + HRA + Allowances">
                </div>

                <div class="form-group">
                    <label>Tax Configuration (%)</label>
                    <input type="number" placeholder="Enter tax %">
                </div>

                <div class="form-group">
                    <label>PF / ESI Settings</label>
                    <input type="text" placeholder="Enter PF/ESI details">
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Payroll Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= EMAIL SETTINGS ================= -->
    <div class="form-section">
        <h2>Email Configuration</h2>
        <form>
            <div class="grid-container">

                <div class="form-group">
                    <label>Mail Driver</label>
                    <select>
                        <option>SMTP</option>
                        <option>Sendmail</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>From Email</label>
                    <input type="email" placeholder="noreply@example.com">
                </div>

                <div class="form-group">
                    <label>From Name</label>
                    <input type="text" placeholder="Company Name">
                </div>

                <div class="form-group">
                    <label>SMTP Host</label>
                    <input type="text" placeholder="smtp.example.com">
                </div>

                <div class="form-group">
                    <label>SMTP Port</label>
                    <input type="number" placeholder="587">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password">
                </div>

                <div class="form-group">
                    <button type="button" class="btn-primary">Send Test Email</button>
                </div>

            </div>

            <div class="form-actions">
                <button type="button" class="btn-primary">Update Email Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= SYSTEM LOGS ================= -->
    <div class="form-section">
        <h2>System Monitoring & Logs</h2>
        <form>
            <div class="form-group">
                <button type="button" class="btn-primary">Audit Logs</button>
                <button type="button" class="btn-primary">Error Logs</button>
                <button type="button" class="btn-primary">Admin Activity</button>
            </div>
        </form>
    </div>


    <!-- ================= PERFORMANCE ================= -->
    <div class="form-section">
        <h2>Performance Settings</h2>
        <form>
            <div class="form-group">
                <button type="button" class="btn-primary">Clear Cache</button>
            </div>

            <div class="form-group">
                <label>Debug Mode</label>
                <select>
                    <option>ON</option>
                    <option>OFF</option>
                </select>
            </div>

            <div class="form-group">
                <label>Queue Monitoring</label>
                <select>
                    <option>Enabled</option>
                    <option>Disabled</option>
                </select>
            </div>
        </form>
    </div>


    <!-- ================= NOTIFICATIONS ================= -->
    <div class="form-section">
        <h2>Global Notifications</h2>
        <form>
            <div class="form-group">
                <label>Email Templates</label>
                <textarea placeholder="Define email templates here..."></textarea>
            </div>

            <div class="form-group">
                <label>Notification Triggers</label>
                <input type="text" placeholder="e.g. Leave Approved, Salary Credited">
            </div>
        </form>
    </div>

</div>
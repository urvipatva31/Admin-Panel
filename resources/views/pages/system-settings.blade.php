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
                    <input type="text" value="IT Solutions Inc.">
                </div>

                <div class="form-group">
                    <label>Company Logo</label>
                    <input type="file">
                </div>

                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" value="admin@itsolutions.com">
                </div>

                <div class="form-group">
                    <label>Company Phone</label>
                    <input type="text" value="+1 123 456 7890">
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
                <button type="submit" class="btn-primary">Update General Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= SECURITY SETTINGS ================= -->
    <div class="form-section">
        <h2>Security Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                
                <div class="form-group">
                    <label>Minimum Password Length</label>
                    <input type="number" value="8" min="6" max="20">
                </div>

                <div class="form-group">
                    <label>Password Must Contain</label>
                    <select>
                        <option>Uppercase + Number</option>
                        <option>Uppercase + Number + Symbol</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Two-Factor Authentication</label>
                    <select>
                        <option>Enabled</option>
                        <option>Disabled</option>
                        <option>Optional</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Session Timeout (Minutes)</label>
                    <input type="number" value="30">
                </div>

                <div class="form-group">
                    <label>Max Login Attempts</label>
                    <input type="number" value="5">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Security Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= ATTENDANCE SETTINGS ================= -->
    <div class="form-section">
        <h2>Attendance Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                
                <div class="form-group">
                    <label>Office Start Time</label>
                    <input type="time" value="09:00">
                </div>

                <div class="form-group">
                    <label>Office End Time</label>
                    <input type="time" value="18:00">
                </div>

                <div class="form-group">
                    <label>Late Mark After (Minutes)</label>
                    <input type="number" value="15">
                </div>

                <div class="form-group">
                    <label>Half Day Threshold (Hours)</label>
                    <input type="number" value="4">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Attendance Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= LEAVE SETTINGS ================= -->
    <div class="form-section">
        <h2>Leave Settings</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                
                <div class="form-group">
                    <label>Annual Leave Days</label>
                    <input type="number" value="18">
                </div>

                <div class="form-group">
                    <label>Sick Leave Days</label>
                    <input type="number" value="12">
                </div>

                <div class="form-group">
                    <label>Casual Leave Days</label>
                    <input type="number" value="10">
                </div>

                <div class="form-group">
                    <label>Leave Approval Required</label>
                    <select>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Leave Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= EMAIL / SMTP SETTINGS ================= -->
    <div class="form-section">
        <h2>Email Configuration</h2>
        <form>
            <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                
                <div class="form-group">
                    <label>SMTP Host</label>
                    <input type="text" placeholder="smtp.mailtrap.io">
                </div>

                <div class="form-group">
                    <label>SMTP Port</label>
                    <input type="number" placeholder="587">
                </div>

                <div class="form-group">
                    <label>SMTP Username</label>
                    <input type="text">
                </div>

                <div class="form-group">
                    <label>SMTP Password</label>
                    <input type="password">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Email Settings</button>
            </div>
        </form>
    </div>


    <!-- ================= SYSTEM MAINTENANCE ================= -->
    <div class="form-section">
        <h2>System Maintenance</h2>
        <form>
            <div class="form-group">
                <label>Maintenance Mode</label>
                <select>
                    <option>Disabled</option>
                    <option>Enabled</option>
                </select>
            </div>

            <div class="form-group">
                <label>System Backup</label>
                <button type="button" class="btn-primary">Generate Backup Now</button>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Maintenance Settings</button>
            </div>
        </form>
    </div>

</div>
<?php
session_start();

if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: employee-login.php");
    exit;
}

$currentUser = $_SESSION['farm_authenticated_session'];

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['farm_authenticated_session']);
    header("Location: employee-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Employee Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script+Swash+Caps&family=Poppins:wght=400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FAFFD1;
            color: #000000;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            box-sizing: border-box;
        }
        header {
            background-color: #11694E;
            padding: 15px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-logo {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }
        .header-title {
            font-family: 'Oleo Script Swash Caps', cursive;
            color: #FFFFFF;
            font-size: 32px;
        }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 35px;
        }
        .nav-anchor {
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
        }
        .nav-btn-login {
            background-color: #FFFFFF;
            color: #000000;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .workspace-wrapper {
            flex: 1;
            padding: 40px 60px;
        }
        .header-status-strip {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 25px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(17, 105, 78, 0.03);
            border: 1px solid rgba(17, 105, 78, 0.08);
            margin-bottom: 30px;
        }
        .portal-brand-title {
            color: #11694E;
            font-size: 26px;
            font-weight: 900;
            margin: 0;
        }
        .portal-welcome-text {
            color: #666666;
            font-size: 14px;
            margin: 4px 0 0 0;
        }
        .status-badge {
            background-color: #11694E;
            color: #FFFFFF;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
        }
        .navigation-tabs-container {
            background-color: #FFFFFF;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(17, 105, 78, 0.06);
            margin-bottom: 30px;
        }
        .tab-link {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            color: #11694E;
            transition: all 0.2s ease;
        }
        .tab-link.active-tab {
            background-color: #11694E;
            color: #FFFFFF;
        }
        .dashboard-metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .metric-card-block {
            background-color: #FFFFFF;
            border-radius: 14px;
            padding: 25px;
            text-align: center;
            border-bottom: 5px solid #11694E;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .metric-card-block.pending-variant { border-bottom-color: #EE7A33; }
        .metric-card-block.success-variant { border-bottom-color: #4CAF50; }
        .metric-card-block.history-variant { border-bottom-color: #2196F3; }
        
        .metric-tag-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .overview-label { color: #11694E; }
        .pending-label { color: #EE7A33; }
        .success-label { color: #4CAF50; }
        .history-label { color: #2196F3; }

        .metric-display-value {
            font-size: 36px;
            font-weight: 900;
            color: #000000;
            margin-bottom: 5px;
        }
        .metric-context-desc {
            font-size: 12px;
            color: #777777;
            font-weight: 600;
        }
        .lower-layout-row {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 25px;
        }
        .content-card-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(17, 105, 78, 0.06);
        }
        .panel-block-title {
            color: #11694E;
            font-size: 20px;
            font-weight: 900;
            margin-top: 0;
            margin-bottom: 25px;
        }
        .profile-data-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .profile-field-item {
            font-size: 14px;
        }
        .profile-field-label {
            font-weight: 700;
            color: #11694E;
            margin-bottom: 4px;
        }
        .profile-field-value {
            color: #000000;
            font-weight: 500;
        }
        .attendance-tracker-box {
            border-top: 1px dashed rgba(17, 105, 78, 0.2);
            margin-top: 15px;
            padding-top: 15px;
        }
        .attendance-row-metric {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .badge-present-status {
            background-color: #E2F3E3;
            color: #2E7D32;
            padding: 3px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
        }
        .signout-btn-tab {
            background-color: #CC3333;
            color: #FFFFFF !important;
            border-radius: 8px;
        }
        footer {
            background-color: #11694E;
            color: #FFFFFF;
            padding: 50px 60px 20px 60px;
            margin-top: auto;
        }
        .footer-upper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            gap: 40px;
        }
        .footer-brand-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        .footer-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .footer-brand-text {
            font-family: 'Oleo Script Swash Caps', cursive;
            color: #FFFFFF;
            font-size: 36px;
        }
        .footer-links-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }
        .footer-section-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .footer-anchor {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
        }
        .footer-newsletter-section {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1.5;
            max-width: 450px;
        }
        .newsletter-desc {
            margin: 0;
            font-size: 15px;
            color: #FFFFFF;
            font-weight: 400;
        }
        .newsletter-form {
            display: flex;
            gap: 12px;
            margin-top: 5px;
            align-items: center;
        }
        .newsletter-input {
            background-color: #E9E7A2;
            border: none;
            padding: 14px 20px;
            border-radius: 30px;
            flex: 1;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #000000;
            outline: none;
        }
        .newsletter-submit {
            background-color: #F4C469;
            color: #000000;
            border: none;
            padding: 14px 28px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .footer-lower {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .copyright-text {
            margin: 0;
            font-size: 14px;
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-brand">
            <img src="logo.png" class="header-logo" alt="Logo">
            <span class="header-title">Animal Farm 360</span>
        </div>
        <nav class="header-nav">
            <a href="#" class="nav-anchor">Contact Us</a>
            <a href="#" class="nav-anchor">Products</a>
            <a href="#" class="nav-anchor">Cart 🛍️</a>
            <a href="employee-dashboard.php" class="nav-btn-login"><?= htmlspecialchars($currentUser['name']) ?></a>
        </nav>
    </header>

    <div class="workspace-wrapper">
        <div class="header-status-strip">
            <div>
                <h1 class="portal-brand-title">Employee Portal</h1>
                <p class="portal-welcome-text">Welcome back to your workspace daily dashboard.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link active-tab">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="dashboard-metrics-grid">
            <div class="metric-card-block">
                <div class="metric-tag-label overview-label">Overview</div>
                <div class="metric-display-value">5</div>
                <div class="metric-context-desc">ASSIGNED TASKS</div>
            </div>
            <div class="metric-card-block pending-variant">
                <div class="metric-tag-label pending-label">Pending</div>
                <div class="metric-display-value">2</div>
                <div class="metric-context-desc">PENDING / ACTIVE</div>
            </div>
            <div class="metric-card-block success-variant">
                <div class="metric-tag-label success-label">Success</div>
                <div class="metric-display-value">3</div>
                <div class="metric-context-desc">COMPLETED TASKS</div>
            </div>
            <div class="metric-card-block history-variant">
                <div class="metric-tag-label history-label">History</div>
                <div class="metric-display-value">96%</div>
                <div class="metric-context-desc">ATTENDANCE MONTH</div>
            </div>
        </div>

        <div class="lower-layout-row">
            <div class="content-card-panel">
                <h3 class="panel-block-title">Personal Profile Information</h3>
                <div class="profile-data-list">
                    <div class="profile-field-item">
                        <div class="profile-field-label">Full Name:</div>
                        <div class="profile-field-value" id="runtimeProfileName"><?= htmlspecialchars($currentUser['name']) ?></div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Employee ID:</div>
                        <div class="profile-field-value" id="runtimeProfileId" style="font-weight: 700;">
                            <?php 
                                if (isset($currentUser['employee_id'])) {
                                    echo htmlspecialchars($currentUser['employee_id']);
                                } elseif (isset($currentUser['id'])) {
                                    echo htmlspecialchars($currentUser['id']);
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Assigned Role:</div>
                        <div class="profile-field-value" id="runtimeProfileRole" style="font-weight: 700; color: #11694E; text-transform: uppercase;">
                            <?php 
                                if (isset($currentUser['designation'])) {
                                    echo htmlspecialchars($currentUser['designation']);
                                } elseif (isset($currentUser['role'])) {
                                    echo htmlspecialchars($currentUser['role']);
                                } else {
                                    echo "GENERAL STAFF";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="profile-field-item">
                        <div class="profile-field-label">Joining Date:</div>
                        <div class="profile-field-value">
                            <?= htmlspecialchars(isset($currentUser['joining_date']) ? $currentUser['joining_date'] : '2025-01-01') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card-panel">
                <h3 class="panel-block-title">Shift Attendance Log</h3>
                <div class="attendance-row-metric">
                    <span style="color: #555555; font-weight: 500;">Check-In:</span>
                    <span style="font-weight: 700; color: #2E7D32;">08:54 AM (On Time)</span>
                </div>
                <div class="attendance-tracker-box">
                    <div class="attendance-row-metric" style="align-items: center; margin-bottom: 0;">
                        <span style="color: #555555; font-weight: 500;">Check-Out Status:</span>
                        <span class="badge-present-status">PRESENT</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-upper">
            <div class="footer-brand-section">
                <img src="logo.png" class="footer-logo" alt="Logo">
                <span class="footer-brand-text">Animal Farm 360</span>
            </div>
            <div class="footer-links-section">
                <span class="footer-section-title">About</span>
                <a href="#" class="footer-anchor">FAQ</a>
                <a href="#" class="footer-anchor">About Us</a>
                <a href="#" class="footer-anchor">Cookie Policy</a>
                <a href="#" class="footer-anchor">Privacy Policy</a>
                <a href="#" class="footer-anchor">Terms & Condition</a>
            </div>
            <div class="footer-newsletter-section">
                <span class="footer-section-title">Newsletter</span>
                <p class="newsletter-desc">Subscribe to our Weekly Newsletter & Receive Latest Update</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed successfully!'); this.reset();">
                    <input type="email" class="newsletter-input" placeholder="Enter your mail here..." required>
                    <button type="submit" class="newsletter-submit">Go</button>
                </form>
            </div>
        </div>
        <div class="footer-lower">
            <p class="copyright-text">&copy; 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>
<?php
session_start();
if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: employee-login.php");
    exit;
}
$currentUser = $_SESSION['farm_authenticated_session'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Profile</title>
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
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .header-title {
            font-family: 'Oleo Script Swash Caps', cursive;
            color: #FFFFFF;
            font-size: 36px;
        }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        .header-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .nav-link-item {
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: opacity 0.2s;
        }
        .nav-link-item:hover {
            opacity: 0.8;
        }
        .cart-link-block {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }
        .cart-emoji-icon {
            font-size: 18px;
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }
        .auth-profile-container {
            display: flex;
            align-items: center;
        }
        .employee-profile-btn {
            background-color: #FFFFFF;
            color: #000000;
            padding: 12px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .workspace-wrapper {
            flex: 1;
            padding: 40px 80px;
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
        .profile-layout-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 30px;
        }
        .avatar-sidebar-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(17, 105, 78, 0.06);
            display: flex;
            flex-direction: column;
            align-items: center;
            height: max-content;
        }
        .avatar-container-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #FAFFD1;
            border: 4px solid #11694E;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .avatar-vector-svg {
            width: 80px;
            height: 80px;
            fill: #11694E;
        }
        .sidebar-user-name {
            font-size: 22px;
            font-weight: 700;
            color: #000000;
            margin: 0 0 5px 0;
            text-transform: capitalize;
        }
        .sidebar-user-id {
            font-size: 16px;
            font-weight: 700;
            color: #11694E;
            margin: 0 0 15px 0;
        }
        .sidebar-status-tag {
            background-color: #E2F3E3;
            color: #2E7D32;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 20px;
            display: inline-block;
        }
        .details-content-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid rgba(17, 105, 78, 0.06);
        }
        .panel-section-divider {
            margin-bottom: 35px;
        }
        .panel-section-divider:last-child {
            margin-bottom: 0;
        }
        .panel-block-title {
            color: #11694E;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 22px;
            border-bottom: 2px solid rgba(17, 105, 78, 0.1);
            padding-bottom: 8px;
        }
        .profile-data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px 30px;
        }
        .profile-field-item {
            display: flex;
            flex-direction: column;
        }
        .profile-field-label {
            font-weight: 600;
            font-size: 14px;
            color: #11694E;
            margin-bottom: 8px;
        }
        .profile-field-value {
            color: #333333;
            font-size: 15px;
            font-weight: 500;
            background-color: #FAFFD1;
            padding: 14px 20px;
            border-radius: 10px;
            border: 1px solid rgba(17, 105, 78, 0.12);
            box-sizing: border-box;
            min-height: 50px;
            display: flex;
            align-items: center;
        }
        .signout-btn-tab {
            background-color: #CC3333;
            color: #FFFFFF !important;
            border-radius: 8px;
        }
        footer {
            background-color: #11694E;
            color: #FFFFFF;
            padding: 60px 80px 25px 80px;
            margin-top: auto;
        }
        .footer-upper {
            display: grid;
            grid-template-columns: 1fr 1fr 1.5fr;
            gap: 60px;
            margin-bottom: 50px;
            align-items: flex-start;
        }
        .footer-brand-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .footer-logo-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .footer-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }
        .footer-brand-text {
            font-family: 'Oleo Script Swash Caps', cursive;
            color: #FFFFFF;
            font-size: 40px;
        }
        .footer-links-section {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .footer-section-title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-anchor {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            opacity: 0.85;
            transition: opacity 0.2s;
        }
        .footer-anchor:hover {
            opacity: 1;
        }
        .footer-newsletter-section {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 450px;
        }
        .newsletter-desc {
            margin: 0;
            font-size: 15px;
            color: #FFFFFF;
            font-weight: 400;
            opacity: 0.9;
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
            padding: 14px 24px;
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
            padding: 14px 32px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .footer-lower {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 25px;
            display: flex;
            justify-content: flex-end;
        }
        .copyright-text {
            margin: 0;
            font-size: 14px;
            color: #FFFFFF;
            opacity: 0.8;
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
            <div class="header-links">
                <a href="#" class="nav-link-item">Contact Us</a>
                <a href="#" class="nav-link-item">Products</a>
                <a href="#" class="cart-link-block">
                    Cart <span class="cart-emoji-icon">🛍️</span>
                </a>
            </div>
            <div class="auth-profile-container">
                <a href="#" class="employee-profile-btn">
                    <?= htmlspecialchars($currentUser['name']) ?>
                </a>
            </div>
        </nav>
    </header>

    <div class="workspace-wrapper">
        <div class="header-status-strip">
            <div>
                <h1 class="portal-brand-title">My Profile</h1>
                <p class="portal-welcome-text">View and verify your registered farm account identity data.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link active-tab">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="profile-layout-container">
            <div class="avatar-sidebar-panel">
                <div class="avatar-container-circle">
                    <svg class="avatar-vector-svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <h2 class="sidebar-user-name" id="avatarNameText"><?= htmlspecialchars($currentUser['name']) ?></h2>
                <div class="sidebar-user-id" id="avatarIdText"><?= htmlspecialchars($currentUser['id']) ?></div>
                <div class="sidebar-status-tag">Active Employee</div>
            </div>

            <div class="details-content-panel">
                <div class="panel-section-divider">
                    <h3 class="panel-block-title">Personal Information</h3>
                    <div class="profile-data-grid">
                        <div class="profile-field-item">
                            <div class="profile-field-label">Full Name</div>
                            <div class="profile-field-value" id="infoFullName"><?= htmlspecialchars($currentUser['name']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Contact Number</div>
                            <div class="profile-field-value" id="infoContactPhone"><?= htmlspecialchars($currentUser['phone'] ?? '345678') ?></div>
                        </div>
                    </div>
                </div>

                <div class="panel-section-divider">
                    <h3 class="panel-block-title">Professional Information</h3>
                    <div class="profile-data-grid">
                        <div class="profile-field-item">
                            <div class="profile-field-label">Employee ID Tag</div>
                            <div class="profile-field-value" id="infoEmployeeId" style="font-weight: 700; color: #11694E;"><?= htmlspecialchars($currentUser['id']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Official Email Address</div>
                            <div class="profile-field-value" id="infoEmailAddress"><?= htmlspecialchars($currentUser['email']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Assigned Work Role</div>
                            <div class="profile-field-value" id="infoAssignedRole" style="font-weight: 700; color: #11694E;"><?= htmlspecialchars($currentUser['role']) ?></div>
                        </div>
                        <div class="profile-field-item">
                            <div class="profile-field-label">Corporate Joining Date</div>
                            <div class="profile-field-value">January 10, 2025</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-upper">
            <div class="footer-brand-section">
                <div class="footer-logo-wrapper">
                    <img src="logo.png" class="footer-logo" alt="Logo">
                    <span class="footer-brand-text">Animal Farm 360</span>
                </div>
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
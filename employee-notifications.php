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
    <title>Animal Farm 360 - Notifications</title>
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script+Swash Caps&family=Poppins:wght=400;500;600;700;900&display=swap" rel="stylesheet">
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
            gap: 5px;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: opacity 0.2s;
        }
        .cart-link-block:hover {
            opacity: 0.8;
        }
        .cart-emoji {
            font-size: 16px;
            display: inline-flex;
            align-items: center;
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
        .notifications-feed-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .notification-alert-card {
            background-color: #FFFFFF;
            border-radius: 12px;
            padding: 20px 25px;
            border-left: 5px solid #11694E;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification-alert-card.announcement-variant {
            border-left-color: #F4C469;
        }
        .alert-message-text {
            margin: 0 0 6px 0;
            font-size: 14px;
            color: #000000;
            font-weight: 600;
        }
        .alert-timestamp-label {
            margin: 0;
            font-size: 12px;
            color: #888888;
            font-weight: 500;
        }
        .dismiss-action-link {
            color: #CC3333;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
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
                    Cart <span class="cart-emoji">🛍️</span>
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
                <h1 class="portal-brand-title">Notifications</h1>
                <p class="portal-welcome-text">Review systematic board updates broadcast by structural administration management.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link active-tab">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="notifications-feed-container" id="notificationsFeedWrapper">
            <div class="notification-alert-card">
                <div>
                    <p class="alert-message-text">System Database Update: Your shift schedule roster metrics have been synchronized successfully.</p>
                    <p class="alert-timestamp-label">2 hours ago</p>
                </div>
                <span class="dismiss-action-link" onclick="dismissCardElement(this)">Dismiss</span>
            </div>

            <div class="notification-alert-card announcement-variant">
                <div>
                    <p class="alert-message-text">Farm Notice: Quarterly biological inventory management check begins this upcoming Monday morning.</p>
                    <p class="alert-timestamp-label">1 day ago</p>
                </div>
                <span class="dismiss-action-link" onclick="dismissCardElement(this)">Dismiss</span>
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

    <script>
        function dismissCardElement(actionNode) {
            const structuralCard = actionNode.closest('.notification-alert-card');
            if(structuralCard) {
                structuralCard.remove();
                
                const container = document.getElementById('notificationsFeedWrapper');
                if(container.children.length === 0) {
                    container.innerHTML = '<div style="text-align: center; color: #888888; padding: 40px; background: white; border-radius: 12px; border: 1px dashed rgba(17,105,78,0.2);">No structural update notifications currently flagged.</div>';
                }
            }
        }
    </script>
</body>
</html>
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
    <title>Employee Portal - Attendance</title>
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
        
        /* Consistent Header Styles */
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

        /* Content Layout */
        .split-layout { 
            display: grid; 
            grid-template-columns: 260px 1fr; 
            flex: 1;
        }
        .sidebar { 
            background: #11694E; 
            padding: 30px 20px; 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
        }
        .sidebar-link { 
            color: #FFFFFF; 
            text-decoration: none; 
            padding: 12px 15px; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 500; 
            display: block; 
            transition: all 0.2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active { 
            background: rgba(250, 255, 209, 0.15); 
            font-weight: 700; 
            color: #FAFFD1; 
        }
        .content-view { 
            padding: 40px 60px; 
            background: #FAFFD1; 
        }
        .log-box { 
            background: #FFFFFF; 
            border-radius: 12px; 
            padding: 30px; 
            border: 1px solid rgba(17, 105, 78, 0.12); 
            margin-bottom: 30px; 
            display: flex; 
            gap: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .action-btn { 
            background: #11694E; 
            color: white; 
            border: none; 
            padding: 12px 25px; 
            border-radius: 6px; 
            font-weight: 700; 
            cursor: pointer; 
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s ease;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
        .out-btn { 
            background: #CC3333; 
        }
        .table-res { 
            overflow-x: auto; 
            background: white; 
            border-radius: 16px; 
            border: 1px solid rgba(17, 105, 78, 0.06); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            padding: 15px;
        }
        .tbl { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
        }
        .tbl th { 
            background: #11694E; 
            color: white; 
            padding: 16px; 
            font-size: 14px; 
            font-weight: 700;
        }
        .tbl td { 
            padding: 16px; 
            border-bottom: 1px solid rgba(17, 105, 78, 0.08); 
            font-size: 14px; 
            color: #333333;
        }

        /* Consistent Footer Styles */
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

    <div class="split-layout">
        <div class="sidebar">
            <a href="employee-dashboard.php" class="sidebar-link">Dashboard</a>
            <a href="employee-profile.php" class="sidebar-link">Profile</a>
            <a href="employee-attendance.php" class="sidebar-link active">Attendance</a>
            <a href="employee-salary.php" class="sidebar-link">Salary Matrix</a>
            <a href="employee-leave.php" class="sidebar-link">Leave Request</a>
            <a href="employee-notifications.php" class="sidebar-link">Notifications</a>
            <a href="employee-dashboard.php?action=logout" class="sidebar-link" style="margin-top: auto; background: rgba(255,0,0,0.15);">Sign Out</a>
        </div>
        
        <div class="content-view">
            <h3 style="color: #11694E; margin-top: 0; font-weight: 900; font-size: 26px;">Attendance Terminal</h3>
            <div class="log-box">
                <button onclick="stampIn()" class="action-btn">Punch Check In</button>
                <button onclick="stampOut()" class="action-btn out-btn">Punch Check Out</button>
            </div>
            <div class="table-res">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date Log</th>
                            <th>Check In Location Timestamp</th>
                            <th>Check Out Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceStream"></tbody>
                </table>
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

    <script>
        let currentUid = "<?= htmlspecialchars($currentUser['id']) ?>";
        document.addEventListener("DOMContentLoaded", function() {
            fetchAttendanceLogs();
        });
        function stampIn() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            const todayStr = new Date().toLocaleDateString();
            const timeStr = new Date().toLocaleTimeString();
            logs.push({ date: todayStr, checkIn: timeStr, checkOut: '--:--' });
            localStorage.setItem('attendance_db_' + currentUid, JSON.stringify(logs));
            fetchAttendanceLogs();
        }
        function stampOut() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            if(logs.length > 0) {
                logs[logs.length - 1].checkOut = new Date().toLocaleTimeString();
                localStorage.setItem('attendance_db_' + currentUid, JSON.stringify(logs));
                fetchAttendanceLogs();
            }
        }
        function fetchAttendanceLogs() {
            let logs = JSON.parse(localStorage.getItem('attendance_db_' + currentUid)) || [];
            const tbody = document.getElementById('attendanceStream');
            tbody.innerHTML = '';
            logs.forEach(function(item) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${item.date}</td><td>${item.checkIn}</td><td>${item.checkOut}</td>`;
                tbody.appendChild(tr);
            });
        }
    </script>
</body>
</html>
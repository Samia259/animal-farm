<?php
session_start();
if (!isset($_SESSION['farm_authenticated_session'])) {
    header("Location: employee-login.php");
    exit;
}
$currentUser = $_SESSION['farm_authenticated_session'];

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['farm_authenticated_session']);
    session_destroy();
    header("Location: employee-login.php");
    exit;
}

$salaryRecords = [
    [
        'cycle' => 'May 2026',
        'base' => 45000,
        'allowances' => 5500,
        'deductions' => 0,
        'status' => 'Paid'
    ],
    [
        'cycle' => 'April 2026',
        'base' => 45000,
        'allowances' => 5500,
        'deductions' => 0,
        'status' => 'Paid'
    ]
];

$latestBase = !empty($salaryRecords) ? number_format($salaryRecords[0]['base']) . ' BDT' : '0 BDT';
$latestAllowances = !empty($salaryRecords) ? number_format($salaryRecords[0]['allowances']) . ' BDT' : '0 BDT';
$latestDisbursalDate = '01/05/2026';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Salary Matrix</title>
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
            gap: 5px;
            background-color: transparent;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
            transition: opacity 0.2s;
        }
        .cart-link-block:hover {
            background-color: transparent;
            transform: none;
            opacity: 0.8;
        }
        .cart-icon-emoji {
            font-size: 16px;
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
        .summary-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card-panel {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border-left: 5px solid #11694E;
        }
        .card-internal-label {
            font-size: 13px;
            font-weight: 700;
            color: #666666;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .card-internal-value {
            font-size: 24px;
            font-weight: 900;
            color: #11694E;
        }
        .data-table-wrapper {
            background: #FFFFFF;
            border: 2px solid #11694E;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .data-matrix {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .data-matrix th {
            background-color: #11694E;
            color: #FFFFFF;
            padding: 16px;
            font-size: 15px;
            font-weight: 700;
        }
        .data-matrix td {
            padding: 16px;
            border-bottom: 1px solid rgba(17, 105, 78, 0.1);
            font-size: 14px;
            color: #000000;
        }
        .badge-status-paid {
            background-color: #E2F3E3;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
        }
        .payslip-download-btn {
            background-color: #FFFFFF;
            color: #11694E;
            border: 2px solid #11694E;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 13px;
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
                    Cart <span class="cart-icon-emoji">🛍️</span>
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
                <h1 class="portal-brand-title">Salary Matrix</h1>
                <p class="portal-welcome-text">Review your base compensations, financial breakdowns, and pay slips.</p>
            </div>
            <div class="status-badge">System Status: Active</div>
        </div>

        <div class="navigation-tabs-container">
            <a href="employee-dashboard.php" class="tab-link">Employee Dashboard</a>
            <a href="employee-profile.php" class="tab-link">Profile</a>
            <a href="employee-attendance.php" class="tab-link">Attendance</a>
            <a href="employee-salary.php" class="tab-link active-tab">Salary Matrix</a>
            <a href="employee-leave.php" class="tab-link">Leave Request</a>
            <a href="employee-notifications.php" class="tab-link">Notifications</a>
            <a href="employee-salary.php?action=logout" class="tab-link signout-btn-tab" style="margin-left: auto;">Logout</a>
        </div>

        <div class="summary-cards-row">
            <div class="summary-card-panel">
                <div>
                    <div class="card-internal-label">Base Monthly Salary</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestBase) ?></div>
                </div>
            </div>
            <div class="summary-card-panel" style="border-left-color: #4CAF50;">
                <div>
                    <div class="card-internal-label">Allowances Disbursed</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestAllowances) ?></div>
                </div>
            </div>
            <div class="summary-card-panel" style="border-left-color: #2196F3;">
                <div>
                    <div class="card-internal-label">Last Disbursal Date</div>
                    <div class="card-internal-value"><?= htmlspecialchars($latestDisbursalDate) ?></div>
                </div>
            </div>
        </div>

        <div class="data-table-wrapper">
            <table class="data-matrix">
                <thead>
                    <tr>
                        <th>Statement Cycle</th>
                        <th>Base Remuneration</th>
                        <th>Allowances</th>
                        <th>Deductions</th>
                        <th>Net Total Payable</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salaryRecords as $record): 
                        $netPayable = $record['base'] + $record['allowances'] - $record['deductions'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($record['cycle']) ?></td>
                            <td><?= htmlspecialchars(number_format($record['base'])) ?> BDT</td>
                            <td><?= htmlspecialchars(number_format($record['allowances'])) ?> BDT</td>
                            <td><?= htmlspecialchars(number_format($record['deductions'])) ?> BDT</td>
                            <td style="font-weight: 700; color: #11694E;"><?= htmlspecialchars(number_format($netPayable)) ?> BDT</td>
                            <td><span class="badge-status-paid"><?= htmlspecialchars($record['status']) ?></span></td>
                            <td><button class="payslip-download-btn" onclick="alert('Downloading billing document payslip statement for <?= htmlspecialchars($record['cycle']) ?>...')">Download</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
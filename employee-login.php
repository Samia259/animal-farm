<?php
session_start();

$host = "localhost";
$dbname = "animalfarmdb";
$username = "root";
$password_db = ""; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['loginEmailField']));
    $password = $_POST['loginPasswordField'];

    $stmt = $conn->prepare("SELECT * FROM employees WHERE LOWER(email) = ? LIMIT 1");
    $stmt->execute([$email]);
    $matchedUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($matchedUser && $matchedUser['password'] === $password) {
        $_SESSION['farm_authenticated_session'] = $matchedUser;
        header("Location: employee-dashboard.php");
        exit;
    } else {
        $error_message = "Authorization failed. Incorrect email reference or security key.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Staff Login Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script+Swash+Caps&family=Poppins:wght=400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #FAFFD1; color: #000000; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; box-sizing: border-box; }
        
        /* Header CSS Updated according to screenshot */
        header { background-color: #11694E; padding: 15px 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header-brand { display: flex; align-items: center; gap: 12px; }
        .header-logo { width: 55px; height: 55px; object-fit: contain; }
        .header-title { font-family: 'Oleo Script Swash Caps', cursive; color: #FFFFFF; font-size: 32px; }
        .header-nav { display: flex; align-items: center; gap: 35px; }
        .nav-anchor { color: #FFFFFF; text-decoration: none; font-weight: 700; font-size: 16px; }
        
        /* Login/Sign Up Button design from screenshot */
        .nav-btn-login { background-color: #FFFFFF; color: #000000; padding: 10px 24px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        
        .auth-wrapper-main { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .auth-container-box { background: #FFFFFF; border-radius: 24px; padding: 45px 40px; width: 100%; max-width: 440px; box-shadow: 0 10px 35px rgba(17, 105, 78, 0.04); border: 1px solid rgba(17, 105, 78, 0.08); text-align: center; box-sizing: border-box; }
        .portal-main-title { color: #11694E; font-size: 28px; font-weight: 900; margin-top: 15px; margin-bottom: 5px; }
        .portal-subtitle-tag { color: #666666; font-size: 14px; margin-top: 0; margin-bottom: 30px; }
        .input-group-stack { text-align: left; margin-bottom: 22px; }
        .input-label-tag { display: block; font-weight: 700; color: #11694E; font-size: 14px; margin-bottom: 8px; }
        .login-field-control { width: 100%; padding: 14px 16px; border: 2px solid rgba(17, 105, 78, 0.2); border-radius: 10px; background-color: #FAFFD1; font-family: 'Poppins', sans-serif; font-size: 14px; color: #000000; outline: none; box-sizing: border-box; }
        .login-field-control:focus { border-color: #11694E; }
        .login-action-submit-btn { width: 100%; background-color: #11694E; color: #FFFFFF; border: none; padding: 15px; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer; margin-top: 10px; box-sizing: border-box; }
        .forgot-password-anchor { display: inline-block; margin-top: 20px; color: #CC3333; text-decoration: none; font-size: 14px; font-weight: 600; }
        .padlock-frame { width: 50px; height: 50px; margin: 0 auto; background: #FAFFD1; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #11694E; }
        .padlock-inner { width: 16px; height: 16px; background: #11694E; border-radius: 3px; position: relative; margin-top: 8px; }
        .padlock-inner::before { content: ''; position: absolute; width: 12px; height: 12px; border: 2px solid #11694E; border-bottom: none; border-top-left-radius: 6px; border-top-right-radius: 6px; top: -10px; left: 0; }
        .error-banner { background-color: #FCE8E6; color: #CC3333; padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px; border: 1px solid rgba(204, 51, 51, 0.2); text-align: left; }
        
        footer { background-color: #11694E; color: #FFFFFF; padding: 50px 60px 20px 60px; margin-top: auto; }
        .footer-upper { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; gap: 40px; }
        .footer-brand-section { display: flex; align-items: center; gap: 15px; flex: 1; }
        .footer-logo { width: 60px; height: 60px; object-fit: contain; }
        .footer-brand-text { font-family: 'Oleo Script Swash Caps', cursive; color: #FFFFFF; font-size: 36px; }
        .footer-links-section { display: flex; flex-direction: column; gap: 12px; flex: 1; }
        .footer-section-title { font-weight: 700; font-size: 16px; margin-bottom: 4px; }
        .footer-anchor { color: #FFFFFF; text-decoration: none; font-size: 15px; font-weight: 400; }
        .footer-newsletter-section { display: flex; flex-direction: column; gap: 12px; flex: 1.5; max-width: 450px; }
        .newsletter-desc { margin: 0; font-size: 15px; color: #FFFFFF; font-weight: 400; }
        .newsletter-form { display: flex; gap: 12px; margin-top: 5px; align-items: center; }
        .newsletter-input { background-color: #E9E7A2; border: none; padding: 14px 20px; border-radius: 30px; flex: 1; font-family: 'Poppins', sans-serif; font-size: 14px; color: #000000; outline: none; }
        .newsletter-submit { background-color: #F4C469; color: #000000; border: none; padding: 14px 28px; font-weight: 600; font-size: 15px; cursor: pointer; border-radius: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .footer-lower { border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px; display: flex; justify-content: flex-end; }
        .copyright-text { margin: 0; font-size: 14px; color: #FFFFFF; }
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
            <a href="admin-dashboard.php" class="nav-btn-login">Login / Sign Up</a>
        </nav>
    </header>

    <div class="auth-wrapper-main">
        <div class="auth-container-box">
            <div class="padlock-frame">
                <div class="padlock-inner"></div>
            </div>
            
            <h2 class="portal-main-title">Staff Login Portal</h2>
            <p class="portal-subtitle-tag">Secure authorization access for farm employees</p>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-banner">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="employee-login.php" autocomplete="off">
                <div class="input-group-stack">
                    <label class="input-label-tag">Registered Email Address</label>
                    <input type="email" name="loginEmailField" id="loginEmailField" placeholder="name@farm360.com" class="login-field-control" autocomplete="off" required>
                </div>
                
                <div class="input-group-stack">
                    <label class="input-label-tag">Secure Password</label>
                    <input type="password" name="loginPasswordField" id="loginPasswordField" placeholder="........" class="login-field-control" autocomplete="new-password" required>
                </div>
                
                <button type="submit" class="login-action-submit-btn">Login to Workspace</button>
            </form>
            
            <a href="#" class="forgot-password-anchor">Forgot Password?</a>
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
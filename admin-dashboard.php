<?php
$host = "localhost";
$dbname = "animalfarmdb";
$username = "root";
$password_db = ""; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div style='color:red; font-weight:bold; text-align:center; margin-top:50px;'>Database Connection Error: " . $e->getMessage() . "</div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $name = trim($_POST['employeeName']);
        $email = trim($_POST['employeeEmail']);
        $phone = trim($_POST['employeePhone']);
        $role = $_POST['employeeRole'];
        $password = $_POST['employeePassword'];
        $editingId = trim($_POST['editingTargetIndex']); 

        if ($_POST['action'] === 'register') {
            if ($editingId === "-1") {
                $stmt = $conn->query("SELECT employee_id FROM employees ORDER BY id DESC LIMIT 1");
                $lastEmployee = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $trackingNum = 1001;
                if ($lastEmployee) {
                    $segments = explode('-', $lastEmployee['employee_id']);
                    if (isset($segments[1])) {
                        $trackingNum = (int)$segments[1] + 1;
                    }
                }
                $generatedId = "AF360-" . $trackingNum;

                $insertStmt = $conn->prepare("INSERT INTO employees (employee_id, name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                $insertStmt->execute([$generatedId, $name, $email, $phone, $password, $role]);
            } else {
                $updateStmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, phone = ?, password = ?, role = ? WHERE id = ?");
                $updateStmt->execute([$name, $email, $phone, $password, $role, $editingId]);
            }
        } 
        header("Location: admin-dashboard.php");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $deleteStmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $deleteStmt->execute([$deleteId]);
    header("Location: admin-dashboard.php");
    exit;
}

$fetchStmt = $conn->query("SELECT * FROM employees ORDER BY id ASC");
$all_workers = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Manage Employee</title>
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script+Swash+Caps&family=Poppins:wght=400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #FAFFD1; color: #000000; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; box-sizing: border-box; }
        header { background-color: #11694E; padding: 15px 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header-brand { display: flex; align-items: center; gap: 12px; }
        .header-logo { width: 55px; height: 55px; object-fit: contain; }
        .header-title { font-family: 'Oleo Script Swash Caps', cursive; color: #FFFFFF; font-size: 32px; }
        .header-nav { display: flex; align-items: center; gap: 35px; }
        .nav-anchor { color: #FFFFFF; text-decoration: none; font-weight: 700; font-size: 16px; }
        .nav-btn-login { background-color: #FFFFFF; color: #000000; padding: 10px 24px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        main { flex: 1; padding: 50px 60px; }
        .main-heading { text-align: center; color: #11694E; font-size: 36px; font-weight: 900; margin-top: 0; margin-bottom: 30px; }
        .panel-container { background: #FAFFD1; border: 2px solid #11694E; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(17, 105, 78, 0.05); }
        .section-subtitle { color: #11694E; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .input-wrap { flex: 1; min-width: 170px; display: flex; flex-direction: column; }
        .field-label { font-weight: 700; font-size: 14px; color: #11694E; margin-bottom: 8px; }
        .box-control { width: 100%; padding: 12px 15px; border: 2px solid rgba(17, 105, 78, 0.3); border-radius: 10px; background-color: #FAFFD1; font-family: 'Poppins', sans-serif; font-size: 14px; box-sizing: border-box; outline: none; color: #000000; }
        .box-control:focus { border-color: #11694E; }
        .register-btn { background-color: #11694E; color: #FFFFFF; border: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; white-space: nowrap; height: 48px; box-sizing: border-box; }
        .search-block { display: flex; gap: 12px; margin-bottom: 25px; max-width: 500px; }
        .search-btn { background-color: #11694E; color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .clear-btn { background-color: #888888; color: white; border: none; padding: 0 20px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .data-table-wrapper { background: #FFFFFF; border: 2px solid #11694E; border-radius: 15px; overflow: hidden; }
        .data-matrix { width: 100%; border-collapse: collapse; text-align: left; }
        .data-matrix th { background-color: #11694E; color: #FFFFFF; padding: 16px; font-size: 15px; font-weight: 700; }
        .data-matrix td { padding: 16px; border-bottom: 1px solid rgba(17, 105, 78, 0.1); font-size: 14px; color: #000000; }
        .data-matrix tr:last-child td { border-bottom: none; }
        .action-container { display: flex; gap: 8px; }
        .row-edit-btn { background-color: #FFFFFF; color: #11694E; border: 2px solid #11694E; padding: 6px 16px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 13px; text-decoration: none; }
        .row-delete-btn { background-color: #CC3333; color: #FFFFFF; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 13px; text-decoration: none; }
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
            <a href="admin-dashboard.php" class="nav-btn-login">Samia</a>
        </nav>
    </header>

    <main>
        <h2 class="main-heading">Dashboard - Manage Employee</h2>

        <div class="panel-container">
            <h3 class="section-subtitle" id="formStateHeader">Add New Staff Member</h3>
            
            <form id="staffSubmissionForm" method="POST" action="admin-dashboard.php" autocomplete="off">
                <input type="hidden" name="action" value="register">
                <input type="hidden" id="editingTargetIndex" name="editingTargetIndex" value="-1">
                
                <div class="form-row">
                    <div class="input-wrap">
                        <label class="field-label">Employee Name</label>
                        <input type="text" id="employeeName" name="employeeName" placeholder="Enter full name" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Email ID</label>
                        <input type="email" id="employeeEmail" name="employeeEmail" placeholder="name@farm360.com" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Contact No</label>
                        <input type="text" id="employeePhone" name="employeePhone" placeholder="01XXXXXXXXX" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Password</label>
                        <input type="text" id="employeePassword" name="employeePassword" placeholder="Enter raw credential password" class="box-control" autocomplete="off" required>
                    </div>
                    <div class="input-wrap">
                        <label class="field-label">Assigned Role</label>
                        <select id="employeeRole" name="employeeRole" class="box-control" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Manager">Manager</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Field Staff">Field Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="register-btn" id="submitActionBtn">Register Staff</button>
                </div>
            </form>
        </div>

        <div class="search-block">
            <input type="text" id="searchFilterBox" class="box-control" placeholder="Type name or role to search...">
            <button onclick="applyTableSearch()" class="search-btn">Search</button>
            <button onclick="clearTableSearch()" class="clear-btn">Clear</button>
        </div>

        <div class="data-table-wrapper">
            <table class="data-matrix">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Staff Name</th>
                        <th>Email Address</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeRecordsTableBody">
                    <?php if (empty($all_workers)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #888888; padding: 25px;">No structural profiles verified within engine storage.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($all_workers as $worker): ?>
                            <tr>
                                <td style="font-weight: 700; color: #11694E;"><?= htmlspecialchars($worker['employee_id']) ?></td>
                                <td><?= htmlspecialchars($worker['name']) ?></td>
                                <td><?= htmlspecialchars($worker['email']) ?></td>
                                <td><?= htmlspecialchars($worker['phone']) ?></td>
                                
                                <td style="font-family: monospace; font-weight: bold; color: #D2691E; background-color: rgba(17, 105, 78, 0.03);">
                                    <?= htmlspecialchars($worker['password']) ?>
                                </td>
                                
                                <td style="font-weight: 700;"><?= htmlspecialchars($worker['role']) ?></td>
                                <td>
                                    <div class="action-container">
                                        <button class="row-edit-btn" onclick='initiateRowEditing(<?= json_encode($worker) ?>)'>Edit</button>
                                        <a class="row-delete-btn" href="admin-dashboard.php?delete=<?= $worker['id'] ?>" onclick="return confirm('Permanently wipe employee record dataset?')">Remove</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

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
        function initiateRowEditing(worker) {
            document.getElementById('editingTargetIndex').value = worker.id;
            document.getElementById('employeeName').value = worker.name;
            document.getElementById('employeeEmail').value = worker.email;
            document.getElementById('employeePhone').value = worker.phone;
            document.getElementById('employeePassword').value = worker.password;
            document.getElementById('employeeRole').value = worker.role;

            document.getElementById('formStateHeader').innerText = "Update Staff Credentials";
            document.getElementById('submitActionBtn').innerText = "Update Data";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function applyTableSearch() {
            const query = document.getElementById('searchFilterBox').value.toLowerCase().trim();
            const rows = document.getElementById('employeeRecordsTableBody').getElementsByTagName('tr');

            for(let i = 0; i < rows.length; i++) {
                if(rows[i].cells.length < 6) continue;
                const idCell = rows[i].cells[0].textContent.toLowerCase();
                const nameCell = rows[i].cells[1].textContent.toLowerCase();
                const roleCell = rows[i].cells[5].textContent.toLowerCase();

                if(idCell.includes(query) || nameCell.includes(query) || roleCell.includes(query)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function clearTableSearch() {
            document.getElementById('searchFilterBox').value = '';
            const rows = document.getElementById('employeeRecordsTableBody').getElementsByTagName('tr');
            for(let i = 0; i < rows.length; i++) {
                rows[i].style.display = '';
            }
        }
    </script>
</body>
</html>
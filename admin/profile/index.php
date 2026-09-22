<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$page_title = 'My Profile';
$admin_id = $_SESSION['admin_id'];

$success_msg = '';
$error_msg = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Update Username
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        
        if (!empty($username)) {
            // Check uniqueness
            $check = $conn->query("SELECT id FROM admins WHERE username = '$username' AND id != $admin_id");
            if ($check->num_rows > 0) {
                $error_msg = "Username already taken.";
            } else {
                $stmt = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
                $stmt->bind_param("si", $username, $admin_id);
                if ($stmt->execute()) {
                    $_SESSION['admin_username'] = $username;
                    $success_msg = "Username updated successfully.";
                } else {
                    $error_msg = "Database error.";
                }
            }
        } else {
            $error_msg = "Username cannot be empty.";
        }
    }

    // Update Password
    if (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        if (!empty($current_pass) && !empty($new_pass) && !empty($confirm_pass)) {
            if ($new_pass === $confirm_pass) {
                // Verify Old
                $res = $conn->query("SELECT password FROM admins WHERE id = $admin_id");
                $row = $res->fetch_assoc();
                
                if (password_verify($current_pass, $row['password'])) {
                    // Update
                    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
                    $stmt->bind_param("si", $new_hash, $admin_id);
                    if ($stmt->execute()) {
                        $success_msg = "Password changed successfully.";
                    } else {
                        $error_msg = "Database error.";
                    }
                } else {
                    $error_msg = "Incorrect current password.";
                }
            } else {
                $error_msg = "New passwords do not match.";
            }
        } else {
            $error_msg = "All password fields are required.";
        }
    }
}

// Fetch Current Data
$admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Amadika Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 0; }
        
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 30px; }
        .form-label { font-weight: 500; color: #555; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.15); border-color: #D4A017; }
        
        .section-title { font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             <?php include '../../admin/includes/header.php'; ?>
             
             <div class="container-fluid px-4 py-4">
                 
                 <div class="row justify-content-center">
                     <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h3 fw-bold text-secondary mb-0">My Profile</h2>
                        </div>
                        
                        <?php if($success_msg): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php echo $success_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error_msg): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Info -->
                        <div class="card card-custom mb-4">
                            <h5 class="section-title">Account Details</h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="update_profile" class="btn btn-primary px-4">Update Details</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Change Password -->
                        <div class="card card-custom">
                            <h5 class="section-title">Change Password</h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="change_password" class="btn btn-danger px-4">Change Password</button>
                                </div>
                            </form>
                        </div>
                        
                     </div>
                 </div>

             </div>
        </div>
    </div>
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

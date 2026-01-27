<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';
require_once '../../admin/includes/header.php';

// Include Autoload for PHPMailer
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success_msg = '';
$error_msg = '';
$test_msg = '';
$test_status = '';

// Fetch Current Settings
$settings = $conn->query("SELECT * FROM smtp_settings LIMIT 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // SAVE SETTINGS
    if (isset($_POST['save_settings'])) {
        $host = $conn->real_escape_string($_POST['host']);
        $port = intval($_POST['port']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password']; // Don't escape, might contain special chars. Use prepared stmt.
        $encryption = $conn->real_escape_string($_POST['encryption']);
        $from_email = $conn->real_escape_string($_POST['from_email']);
        $from_name = $conn->real_escape_string($_POST['from_name']);
        
        $stmt = $conn->prepare("UPDATE smtp_settings SET host=?, port=?, username=?, password=?, encryption=?, from_email=?, from_name=? WHERE id=?");
        $id = $settings['id'];
        $stmt->bind_param("sisssssi", $host, $port, $username, $password, $encryption, $from_email, $from_name, $id);
        
        if ($stmt->execute()) {
            $success_msg = "SMTP Settings Saved Successfully!";
            // Refresh settings
            $settings = $conn->query("SELECT * FROM smtp_settings LIMIT 1")->fetch_assoc();
        } else {
            $error_msg = "Error Saving: " . $conn->error;
        }
    }
    
    // SEND TEST EMAIL
    if (isset($_POST['send_test'])) {
        $test_email = filter_var($_POST['test_email'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            $test_status = 'error';
            $test_msg = 'Invalid Test Email Address';
        } else {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = $settings['host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $settings['username'];
                $mail->Password   = $settings['password'];
                $mail->SMTPSecure = $settings['encryption']; 
                $mail->Port       = $settings['port'];
                
                // For debug (optional, maybe too verbose for UI)
                // $mail->SMTPDebug = 2; 

                // Recipients
                $mail->setFrom($settings['from_email'], $settings['from_name']);
                $mail->addAddress($test_email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'SMTP Test - Amadika Admin';
                $mail->Body    = '<h3>SMTP Configuration Test</h3><p>This is a test email sent from the Amadika Admin Panel. If you are reading this, your SMTP configuration is correct.</p>';

                $mail->send();
                $test_status = 'success';
                $test_msg = 'Test email sent successfully to ' . $test_email;
            } catch (Exception $e) {
                $test_status = 'error';
                $test_msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage SMTP - Amadika Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 20px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 25px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             
             <div class="container-fluid mt-4">
                <h2 class="h3 fw-bold text-secondary mb-4">SMTP Configuration</h2>

                <div class="row">
                    <!-- Config Form -->
                    <div class="col-lg-7 mb-4">
                        <div class="card card-custom h-100">
                             <h5 class="card-title fw-bold mb-4 text-primary"><i class="fas fa-cogs me-2"></i> Mail Server Settings</h5>
                             
                             <?php if($success_msg): ?>
                                <div class="alert alert-success"><?php echo $success_msg; ?></div>
                             <?php endif; ?>
                             <?php if($error_msg): ?>
                                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                             <?php endif; ?>

                             <form method="POST">
                                 <div class="row">
                                     <div class="col-md-8 mb-3">
                                         <label class="form-label">SMTP Host</label>
                                         <input type="text" class="form-control" name="host" value="<?php echo htmlspecialchars($settings['host']); ?>" required placeholder="smtp.gmail.com">
                                     </div>
                                     <div class="col-md-4 mb-3">
                                         <label class="form-label">Port</label>
                                         <input type="number" class="form-control" name="port" value="<?php echo htmlspecialchars($settings['port']); ?>" required placeholder="587">
                                     </div>
                                 </div>

                                 <div class="row">
                                     <div class="col-md-6 mb-3">
                                         <label class="form-label">Username (Email)</label>
                                         <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($settings['username']); ?>" required>
                                     </div>
                                     <div class="col-md-6 mb-3">
                                         <label class="form-label">Password</label>
                                         <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($settings['password']); ?>" required>
                                     </div>
                                 </div>

                                 <div class="mb-3">
                                     <label class="form-label">Encryption</label>
                                     <select class="form-select" name="encryption">
                                         <option value="tls" <?php echo $settings['encryption'] == 'tls' ? 'selected' : ''; ?>>TLS (Recommended)</option>
                                         <option value="ssl" <?php echo $settings['encryption'] == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                         <option value="none" <?php echo $settings['encryption'] == 'none' ? 'selected' : ''; ?>>None</option>
                                     </select>
                                 </div>

                                 <div class="row">
                                     <div class="col-md-6 mb-3">
                                         <label class="form-label">From Email</label>
                                         <input type="email" class="form-control" name="from_email" value="<?php echo htmlspecialchars($settings['from_email']); ?>" required>
                                     </div>
                                     <div class="col-md-6 mb-3">
                                         <label class="form-label">From Name</label>
                                         <input type="text" class="form-control" name="from_name" value="<?php echo htmlspecialchars($settings['from_name']); ?>" required>
                                     </div>
                                 </div>

                                 <div class="text-end">
                                     <button type="submit" name="save_settings" class="btn btn-primary px-4">Save Configuration</button>
                                 </div>
                             </form>
                        </div>
                    </div>

                    <!-- Test Form -->
                    <div class="col-lg-5 mb-4">
                        <div class="card card-custom h-100 bg-light">
                            <h5 class="card-title fw-bold mb-4 text-success"><i class="fas fa-paper-plane me-2"></i> Test Configuration</h5>
                            <p class="text-muted small">Send a test email to verify your SMTP configuration is working correctly before using it in production.</p>
                            
                             <?php if($test_msg): ?>
                                <div class="alert alert-<?php echo $test_status == 'success' ? 'success' : 'danger'; ?>"><?php echo $test_msg; ?></div>
                             <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Send Test To</label>
                                    <input type="email" class="form-control" name="test_email" placeholder="enter.email@example.com" required>
                                </div>
                                <button type="submit" name="send_test" class="btn btn-success w-100">Send Test Email</button>
                            </form>
                            
                            <hr class="my-4">
                            
                            <div class="small text-muted">
                                <strong>Common Ports:</strong><br>
                                TLS: 587<br>
                                SSL: 465
                            </div>
                        </div>
                    </div>
                </div>

             </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';
require_once '../../admin/includes/header.php';

$success_msg = '';
$error_msg = '';

// Fetch Current Settings
$settings = $conn->query("SELECT * FROM razorpay_settings LIMIT 1")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key_id = $conn->real_escape_string($_POST['key_id']);
    $key_secret = $conn->real_escape_string($_POST['key_secret']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $stmt = $conn->prepare("UPDATE razorpay_settings SET key_id=?, key_secret=?, status=? WHERE id=?");
    $id = $settings['id'];
    $stmt->bind_param("sssi", $key_id, $key_secret, $status, $id);
    
    if ($stmt->execute()) {
        $success_msg = "Razorpay Settings Saved Successfully!";
        // Refresh settings
        $settings = $conn->query("SELECT * FROM razorpay_settings LIMIT 1")->fetch_assoc();
    } else {
        $error_msg = "Error Saving: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Razorpay - Amadika Admin</title>
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
    
    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             
             <div class="container-fluid mt-4">
                <h2 class="h3 fw-bold text-secondary mb-4">Razorpay Configuration</h2>

                <div class="row">
                    <!-- Config Form -->
                    <div class="col-lg-7 mb-4">
                        <div class="card card-custom h-100">
                             <h5 class="card-title fw-bold mb-4 text-primary"><i class="fas fa-credit-card me-2"></i> Payment Gateway Settings</h5>
                             
                             <?php if($success_msg): ?>
                                <div class="alert alert-success"><?php echo $success_msg; ?></div>
                             <?php endif; ?>
                             <?php if($error_msg): ?>
                                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                             <?php endif; ?>

                             <form method="POST">
                                 <div class="mb-3">
                                     <label class="form-label">Key ID</label>
                                     <input type="text" class="form-control" name="key_id" value="<?php echo htmlspecialchars($settings['key_id']); ?>" required placeholder="rzp_test_...">
                                 </div>

                                 <div class="mb-3">
                                     <label class="form-label">Key Secret</label>
                                     <input type="text" class="form-control" name="key_secret" value="<?php echo htmlspecialchars($settings['key_secret']); ?>" required>
                                 </div>

                                 <div class="mb-3">
                                     <label class="form-label">Status</label>
                                     <select class="form-select" name="status">
                                         <option value="active" <?php echo $settings['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                         <option value="inactive" <?php echo $settings['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                     </select>
                                 </div>

                                 <div class="text-end">
                                     <button type="submit" class="btn btn-primary px-4">Save Configuration</button>
                                 </div>
                             </form>
                        </div>
                    </div>

                    <!-- Test Payment -->
                    <div class="col-lg-5 mb-4">
                        <div class="card card-custom h-100 bg-light">
                            <h5 class="card-title fw-bold mb-4 text-success"><i class="fas fa-vial me-2"></i> Test Payment</h5>
                            <p class="text-muted small">Initiate a test payment of <b>₹1.00</b> to verify that your Keys are working correctly.</p>
                            
                            <div class="alert alert-warning small">
                                <strong>Note:</strong> Ensure you are using <b>Test Keys</b> to avoid actual charges. If using Live keys, ₹1 will be deducted.
                            </div>

                            <button id="rzp-button1" class="btn btn-success w-100 py-2">Pay ₹1.00 Now</button>
                            
                            <div id="payment-status" class="mt-3 text-center fw-bold"></div>
                        </div>
                    </div>
                </div>

             </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('rzp-button1').onclick = function(e){
        var options = {
            "key": "<?php echo $settings['key_id']; ?>", 
            "amount": "100", // Amount is in currency subunits. Default currency is INR. Hence 100 paise = INR 1
            "currency": "INR",
            "name": "Amadika Test",
            "description": "Admin Test Transaction",
            "image": "../../assets/images/amdika-logo.png",
            "handler": function (response){
                document.getElementById('payment-status').innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Payment Successful! ID: ' + response.razorpay_payment_id + '</span>';
                // You can add logic here to verify signature via ajax if needed, but for simple connectivity test, this confirms Key ID works.
            },
            "prefill": {
                "name": "Admin Tester",
                "email": "admin@example.com",
                "contact": "9999999999"
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response){
                document.getElementById('payment-status').innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Payment Failed: ' + response.error.description + '</span>';
        });
        rzp1.open();
        e.preventDefault();
    }
    </script>
</body>
</html>

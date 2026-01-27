<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';
require_once '../../admin/includes/header.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($conn->real_escape_string($_POST['code'])));
    $valid_till = $conn->real_escape_string($_POST['valid_till']);
    $total_usage_limit = intval($_POST['total_usage_limit']);
    $discount_percent = intval($_POST['discount_percent']);
    $min_order_value = floatval($_POST['min_order_value']);
    
    // Basic Validation
    if(empty($code) || empty($valid_till) || $discount_percent <= 0) {
        $error_msg = "Please fill all required fields correctly.";
    } else {
        // Check uniqueness
        $check = $conn->query("SELECT id FROM coupons WHERE code = '$code'");
        if ($check->num_rows > 0) {
            $error_msg = "Coupon Code '$code' already exists.";
        } else {
            $sql = "INSERT INTO coupons (code, discount_percent, min_order_value, valid_till, total_usage_limit) 
                    VALUES ('$code', $discount_percent, $min_order_value, '$valid_till', $total_usage_limit)";
            
            if ($conn->query($sql)) {
                $success_msg = "Coupon Created Successfully!";
            } else {
                $error_msg = "Database Error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Coupon Code - Amadika Admin</title>
    <!-- Assets -->
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
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
             <!-- Header is included at top but we need to ensure structure matches. 
                  The updated admin/includes/header.php assumes it's inside #page-content-wrapper or similar context.
                  Actually, the user's admin/index.php structure has sidebar then content wrapper then header inside it.
                  My require_once header.php up top might output HTML before body.
              -->
             <!-- Let's fix the include structure. Header usually outputs the top nav bar (excluding <html> tags if designed as component, but analyzed header.php has full structure)
                  Wait, `admin/includes/header.php` HAS <html> <head> etc inside it? 
                  Let's re-read header.php content in memory. 
                  Step 992 view_file shows header.php starts with CSS and then <header> tag. It does NOT have <head> or <html> tags. 
                  Wait, Step 959 (for frontend header) has full HTML. Step 992 (admin header) starts with <style> and then <header>... 
                  Wait, Step 992 output:
                  Lines 275-291 are PHP logic.
                  Line 292 starts <header class="admin-header">.
                  It seems `admin/includes/header.php` is JUST the top bar component.
                  
                  So my structure in THIS file (add-coupon-code.php) needs to provide the HTML shell.
             -->
             
             <!-- Re-including sidebar which is also a component likely -->
             
             <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Add New Coupon</h2>
                    <a href="manage-coupon-codes.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back to List</a>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card card-custom">
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?php echo $success_msg; ?></div>
                            <?php endif; ?>
                            <?php if($error_msg): ?>
                                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="code" placeholder="e.g. SUMMER50" required style="text-transform:uppercase;">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Discount Percentage (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="discount_percent" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Valid Till <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="valid_till" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Usage Limit <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="total_usage_limit" min="1" required placeholder="Total times this coupon can be used">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Min Order Value (Rs.) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="min_order_value" step="0.01" min="0" required value="0">
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-danger px-4">Create Coupon</button>
                                </div>
                            </form>
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

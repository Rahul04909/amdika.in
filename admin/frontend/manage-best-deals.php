<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$success_msg = '';
$error_msg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_cats = isset($_POST['categories']) ? $_POST['categories'] : [];
    // Sanitize
    $clean_cats = array_map('intval', $selected_cats);
    $json_cats = json_encode($clean_cats);

    // Update settings
    // Assuming ID 1 is the default row, or update the first found row
    $sql = "UPDATE best_deals_settings SET category_ids = '$json_cats' WHERE id = 1"; 
    // If you want to be more robust, check if row exists first, but our setup script ensured it.
    
    if ($conn->query($sql)) {
        $success_msg = "Settings updated successfully!";
    } else {
        $error_msg = "Error updating settings: " . $conn->error;
    }
}

// Fetch Categories
$cats_result = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC");

// Fetch Current Settings
$settings_result = $conn->query("SELECT category_ids FROM best_deals_settings WHERE id = 1");
$current_cats = [];
if ($settings_result && $settings_result->num_rows > 0) {
    $row = $settings_result->fetch_assoc();
    $current_cats = json_decode($row['category_ids'], true) ?: [];
}

$page_title = 'Manage Best Deals Component';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Best Deals - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 20px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 25px; }
        .cat-checkbox-label {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 8px;
            display: block;
            position: relative;
            transition: all 0.2s;
        }
        .cat-checkbox-label:hover {
            background-color: #f9f9f9;
            border-color: #ddd;
        }
        .cat-checkbox:checked + .cat-checkbox-label {
            background-color: #ffebee;
            border-color: #D32F2F;
            color: #D32F2F;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Manage Best Deals Component</h2>
                </div>

                <div class="card card-custom">
                    <p class="text-muted mb-4">Select the categories whose products should appear in the "Best Deals" section on the frontend.</p>
                    
                    <form method="POST">
                        <div class="row g-3">
                            <?php if ($cats_result->num_rows > 0): ?>
                                <?php while($cat = $cats_result->fetch_assoc()): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <input type="checkbox" class="btn-check cat-checkbox" 
                                               id="cat_<?php echo $cat['id']; ?>" 
                                               name="categories[]" 
                                               value="<?php echo $cat['id']; ?>"
                                               autocomplete="off"
                                               <?php echo in_array($cat['id'], $current_cats) ? 'checked' : ''; ?>>
                                        <label class="cat-checkbox-label text-center" for="cat_<?php echo $cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning">No categories found. Please add categories first.</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-5 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4"><i class="fas fa-save me-2"></i>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>', confirmButtonColor: '#D32F2F' });
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $hex_code = $conn->real_escape_string($_POST['hex_code']);

    $sql = "INSERT INTO colors (name, hex_code) VALUES ('$name', '$hex_code')";
    if ($conn->query($sql)) {
        $success_msg = "Color added successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Color - Amadika Admin</title>
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 25px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            <div class="container-fluid mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h3 fw-bold text-secondary">Add New Color</h2>
                            <a href="manage-colors.php" class="btn btn-secondary btn-sm">Back</a>
                        </div>
                        <div class="card card-custom">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Color Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="e.g. Royal Blue" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="hex_code" value="#D32F2F" title="Choose your color">
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Add Color</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>' }).then(() => { window.location.href = 'manage-colors.php'; });
        <?php endif; ?>
        <?php if($error_msg): ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

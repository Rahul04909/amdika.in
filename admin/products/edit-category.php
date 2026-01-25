<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_msg = '';
$error_msg = '';

// Fetch Category Data
$sql = "SELECT * FROM product_categories WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: manage-category.php");
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : $name;
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    
    // Check for unique slug excluding current ID
    $check_slug = "SELECT id FROM product_categories WHERE slug = '$slug' AND id != $id";
    if ($conn->query($check_slug)->num_rows > 0) {
        $slug .= '-' . time();
    }
    
    $description = $conn->real_escape_string($_POST['description']);
    $seo_title = $conn->real_escape_string($_POST['seo_title']);
    $seo_description = $conn->real_escape_string($_POST['seo_description']);
    $seo_keywords = $conn->real_escape_string($_POST['seo_keywords']);

    // Handle Image Update
    $image_update_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../assets/images/categories/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "cat_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Delete old image
            if (!empty($row['image']) && file_exists("../../" . $row['image'])) {
                unlink("../../" . $row['image']);
            }
            $image_path = "assets/images/categories/" . $new_filename;
            $image_update_sql = ", image = '$image_path'";
        }
    }

    // Handle SEO Image Update
    $seo_image_update_sql = "";
    if (isset($_FILES['seo_featured_image']) && $_FILES['seo_featured_image']['error'] == 0) {
        $target_dir = "../../assets/images/categories/seo/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES["seo_featured_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "seo_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["seo_featured_image"]["tmp_name"], $target_file)) {
             if (!empty($row['seo_featured_image']) && file_exists("../../" . $row['seo_featured_image'])) {
                unlink("../../" . $row['seo_featured_image']);
            }
            $seo_image_path = "assets/images/categories/seo/" . $new_filename;
            $seo_image_update_sql = ", seo_featured_image = '$seo_image_path'";
        }
    }

    $sql = "UPDATE product_categories SET 
            name = '$name', 
            slug = '$slug', 
            description = '$description',
            seo_title = '$seo_title', 
            seo_description = '$seo_description', 
            seo_keywords = '$seo_keywords',
            updated_at = NOW()
            $image_update_sql
            $seo_image_update_sql
            WHERE id = $id";

    if ($conn->query($sql)) {
        $success_msg = "Category updated successfully!";
        // Refresh data
        $row = $conn->query("SELECT * FROM product_categories WHERE id = $id")->fetch_assoc();
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$page_title = 'Edit Category';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category - Amadika Admin</title>
    <!-- CSP & Assets -->
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
        .form-label { font-weight: 500; color: #2D3436; }
        .preview-img { max-width: 150px; border-radius: 8px; border: 1px solid #ddd; margin-top: 10px; }
        .nav-tabs .nav-link.active { color: #D32F2F; border-bottom: 2px solid #D32F2F; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Edit Category</h2>
                    <a href="manage-category.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data">
                        <ul class="nav nav-tabs mb-4" id="catTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">Basic Info</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">SEO</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <!-- Basic Info -->
                            <div class="tab-pane fade show active" id="basic">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required onkeyup="generateSlug(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug" id="slug" value="<?php echo htmlspecialchars($row['slug']); ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="editor"><?php echo htmlspecialchars($row['description']); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                    <?php if(!empty($row['image'])): ?>
                                        <div class="mb-2">
                                            <img src="../../<?php echo $row['image']; ?>" class="preview-img">
                                        </div>
                                    <?php endif; ?>
                                    <label class="form-label small text-muted">Change Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this, 'imgPreview')">
                                    <img id="imgPreview" class="preview-img" style="display:none;">
                                </div>
                            </div>
                            
                            <!-- SEO -->
                            <div class="tab-pane fade" id="seo">
                                <div class="mb-3">
                                    <label class="form-label">SEO Title</label>
                                    <input type="text" class="form-control" name="seo_title" value="<?php echo htmlspecialchars($row['seo_title']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Description</label>
                                    <textarea class="form-control" name="seo_description" rows="3"><?php echo htmlspecialchars($row['seo_description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords" value="<?php echo htmlspecialchars($row['seo_keywords']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Featured Image</label>
                                    <?php if(!empty($row['seo_featured_image'])): ?>
                                        <div class="mb-2">
                                            <img src="../../<?php echo $row['seo_featured_image']; ?>" class="preview-img">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="seo_featured_image" accept="image/*" onchange="previewImage(this, 'seoPreview')">
                                    <img id="seoPreview" class="preview-img" style="display:none;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-warning px-4 text-white">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script>
        if(document.getElementById('editor')) CKEDITOR.replace('editor');
        
        // Only auto-generate slug if user hasn't manually edited it significantly (simple heuristic)
        function generateSlug(text) {
            // Optional: You might want to disable auto-update on edit if specific URL preservation is desired.
            // document.getElementById('slug').value = text.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        }

        function previewImage(input, imgId) {
            const preview = document.getElementById(imgId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Updated!', text: '<?php echo $success_msg; ?>', confirmButtonColor: '#D32F2F' })
            .then(() => { window.location.href = 'manage-category.php'; });
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

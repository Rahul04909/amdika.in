<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);

    // Auto-generate slug if empty, else clean it
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : $name;
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));

    $description = $conn->real_escape_string($_POST['description']);

    // SEO Fields
    $seo_title = $conn->real_escape_string($_POST['seo_title']);
    $seo_description = $conn->real_escape_string($_POST['seo_description']);
    $seo_keywords = $conn->real_escape_string($_POST['seo_keywords']);

    // Handle Image Upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../assets/images/categories/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "cat_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "assets/images/categories/" . $new_filename;
        }
    }

    // Handle SEO Featured Image
    $seo_image_path = '';
    if (isset($_FILES['seo_featured_image']) && $_FILES['seo_featured_image']['error'] == 0) {
        $target_dir = "../../assets/images/categories/seo/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $file_ext = strtolower(pathinfo($_FILES["seo_featured_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "seo_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["seo_featured_image"]["tmp_name"], $target_file)) {
            $seo_image_path = "assets/images/categories/seo/" . $new_filename;
        }
    }

    $sql = "INSERT INTO product_categories (name, slug, description, image, seo_title, seo_description, seo_keywords, seo_featured_image) 
            VALUES ('$name', '$slug', '$description', '$image_path', '$seo_title', '$seo_description', '$seo_keywords', '$seo_image_path')";

    if ($conn->query($sql)) {
        $success_msg = "Category added successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$page_title = 'Add New Category';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Category - Amadika Admin</title>

    <!-- CSP & Assets -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background-color: #f5f7fa;
        }

        .wrapper {
            display: flex;
        }

        #page-content-wrapper {
            width: 100%;
            padding: 20px;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 25px;
        }

        .form-label {
            font-weight: 500;
            color: #2D3436;
        }

        .nav-tabs .nav-link {
            color: #636E72;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #D32F2F;
            border-bottom: 2px solid #D32F2F;
        }

        .preview-img {
            max-width: 150px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 10px;
            display: none;
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
                    <h2 class="h3 fw-bold text-secondary">Add New Category</h2>
                    <a href="manage-category.php" class="btn btn-secondary"><i
                            class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data" id="addCategoryForm">
                        <ul class="nav nav-tabs mb-4" id="catTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                    data-bs-target="#basic" type="button" role="tab">Basic Info</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                    type="button" role="tab">SEO</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="catTabsContent">
                            <!-- Basic Info -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" required
                                            onkeyup="generateSlug(this.value)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Slug (URL)</label>
                                        <input type="text" class="form-control" name="slug" id="slug">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="editor"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*"
                                        onchange="previewImage(this, 'imgPreview')">
                                    <img id="imgPreview" class="preview-img">
                                </div>
                            </div>

                            <!-- SEO Info -->
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">SEO Meta Title</label>
                                    <input type="text" class="form-control" name="seo_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Meta Description</label>
                                    <textarea class="form-control" name="seo_description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords"
                                        placeholder="keyword1, keyword2, ...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Featured Image</label>
                                    <input type="file" class="form-control" name="seo_featured_image" accept="image/*"
                                        onchange="previewImage(this, 'seoPreview')">
                                    <img id="seoPreview" class="preview-img">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Assets -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor -->
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script>
        // Init CKEditor
        if (document.getElementById('editor')) {
            CKEDITOR.config.versionCheck = false;
            CKEDITOR.replace('editor');
        }

        // Slug Generator
        function generateSlug(text) {
            let slug = text.toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            document.getElementById('slug').value = slug;
        }

        // Image Preview
        function previewImage(input, imgId) {
            const preview = document.getElementById(imgId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }

        // SweetAlert Success/Error
        <?php if ($success_msg): ?>
            Swal.fire({
                icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>',
                confirmButtonColor: '#D32F2F'
            }).then(() => { window.location.href = 'manage-category.php'; });
        <?php endif; ?>

        <?php if ($error_msg): ?>
            Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>

</html>
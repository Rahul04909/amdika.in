<?php
require_once __DIR__ . '/../../admin/includes/auth.php';
require_once __DIR__ . '/../../database/db_config.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = !empty(trim($_POST['author'])) ? $_POST['author'] : 'Admin';
    $status = $_POST['status'];
    
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : $title;
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    
    // Check for Duplicate Slug and append unique suffix if needed
    $chk = $conn->query("SELECT id FROM blogs WHERE slug = '$slug'");
    if($chk && $chk->num_rows > 0) {
        $slug .= '-' . time();
    }
    
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $seo_title = $_POST['seo_title'];
    $seo_description = $_POST['seo_description'];
    $seo_keywords = $_POST['seo_keywords'];

    // Handle Cover Image Upload
    $featured_img_path = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $target_dir = "../../assets/images/blogs/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = strtolower(pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "blog_" . time() . "." . $ext;
        if(move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_dir . $new_name)){
            $featured_img_path = "assets/images/blogs/" . $new_name;
        }
    }

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO blogs (
            title, slug, summary, content, featured_image, author, 
            seo_title, seo_description, seo_keywords, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception($conn->error);

        $stmt->bind_param("ssssssssss", 
            $title, $slug, $summary, $content, $featured_img_path, $author,
            $seo_title, $seo_description, $seo_keywords, $status
        );

        if (!$stmt->execute()) throw new Exception($stmt->error);

        $conn->commit();
        $success_msg = "Blog post published successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $error_msg = "Error: " . $e->getMessage();
    }
}

$page_title = 'Add New Blog';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blog - Amadika Admin</title>
    <!-- Assets -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://via.placeholder.com; connect-src 'self' https://cke4.ckeditor.com;">
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/images/amdika-logo.png">

    <style>
        body { font-family: 'Rubik', sans-serif; background-color: #f5f7fa; }
        .wrapper { display: flex; }
        #page-content-wrapper { width: 100%; padding: 20px; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 25px; }
        .nav-tabs .nav-link { color: #636E72; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #D32F2F; border-bottom: 2px solid #D32F2F; }
        .preview-img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin: 5px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Add New Blog Post</h2>
                    <a href="manage-blogs.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data" id="addBlogForm">
                        <ul class="nav nav-tabs mb-4" id="blogTabs" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button">General</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#media" type="button">Cover Image</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo" type="button">SEO Optimization</button></li>
                        </ul>

                        <div class="tab-content">
                            <!-- General -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Blog Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" id="blogTitle" required onkeyup="generateSlug(this.value)">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            <option value="active">Active (Published)</option>
                                            <option value="inactive">Inactive (Draft)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug" id="slug" placeholder="auto-generated-slug-from-title">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Author</label>
                                        <input type="text" class="form-control" name="author" placeholder="Admin" value="Admin">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Summary / Excerpt</label>
                                        <textarea class="form-control" name="summary" rows="3" placeholder="Brief summary of the blog post..."></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="content" id="editor" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Media -->
                            <div class="tab-pane fade" id="media">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image / Cover Image</label>
                                    <input type="file" class="form-control" name="featured_image" accept="image/*" onchange="previewImage(this, 'featPreview')">
                                    <small class="text-muted d-block mt-1">Recommended size: 800x600px or larger. PNG, JPG, JPEG, WEBP.</small>
                                    <img id="featPreview" class="preview-img mt-3" style="display:none;">
                                </div>
                            </div>

                            <!-- SEO Settings -->
                            <div class="tab-pane fade" id="seo">
                                <div class="mb-3">
                                    <label class="form-label">SEO Title (Meta Title)</label>
                                    <input type="text" class="form-control" name="seo_title" placeholder="If left blank, blog title will be used.">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Description (Meta Description)</label>
                                    <textarea class="form-control" name="seo_description" rows="3" placeholder="Search engine description (150-160 characters)..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords" placeholder="e.g. leather, premium storage, home decor">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4">Publish Blog</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script>
        // Init CKEditor
        if(document.getElementById('editor')) {
            CKEDITOR.config.versionCheck = false;
            CKEDITOR.replace('editor');
        }

        // Slug Gen
        function generateSlug(text) {
           let slug = text.toLowerCase()
                          .replace(/[^a-z0-9\s-]/g, '') // remove special chars
                          .replace(/\s+/g, '-')         // replace spaces with -
                          .replace(/-+/g, '-')          // replace multiple - with single -
                          .replace(/^-|-$/g, '');       // trim - from ends
           document.getElementById('slug').value = slug;
        }

        // Image Preview
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
        Swal.fire({ 
            icon: 'success', 
            title: 'Success', 
            text: '<?php echo htmlspecialchars($success_msg); ?>', 
            confirmButtonColor: '#D32F2F' 
        }).then(() => { 
            window.location.href = 'manage-blogs.php'; 
        });
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        Swal.fire({ 
            icon: 'error', 
            title: 'Error', 
            text: '<?php echo htmlspecialchars($error_msg); ?>' 
        });
        <?php endif; ?>
    </script>
</body>
</html>

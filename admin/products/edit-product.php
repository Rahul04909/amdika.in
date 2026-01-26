<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id == 0) { header("Location: manage-products.php"); exit; }

// Fetch Product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$prod = $stmt->get_result()->fetch_assoc();
if(!$prod) { header("Location: manage-products.php"); exit; }

// Fetch Categories
$cats = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC");

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = intval($_POST['category_id']);
    
    // Slug logic (only update if changed manually to avoid breaking links, or strict update)
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : $name;
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    
    // Check unique slug (exclude self)
    $chk = $conn->query("SELECT id FROM products WHERE slug = '$slug' AND id != $id");
    if($chk->num_rows > 0) $slug .= '-' . time();

    $description = $conn->real_escape_string($_POST['description']);
    $mrp = floatval($_POST['mrp']);
    $sale_price = floatval($_POST['sale_price']);
    $discount_percent = 0;
    if ($mrp > 0 && $sale_price > 0 && $mrp > $sale_price) {
        $discount_percent = round((($mrp - $sale_price) / $mrp) * 100);
    }
    
    $video_url = $conn->real_escape_string($_POST['video_url']);
    $status = $_POST['status'];
    $seo_title = $conn->real_escape_string($_POST['seo_title']);
    $seo_description = $conn->real_escape_string($_POST['seo_description']);
    $seo_keywords = $conn->real_escape_string($_POST['seo_keywords']);
    $schema_markup = $conn->real_escape_string($_POST['schema_markup']);

    // 1. Featured Image Update
    $featured_sql = "";
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $target_dir = "../../assets/images/products/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $ext = strtolower(pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "prod_" . time() . "_feat." . $ext;
        if(move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_dir . $new_name)){
            // Delete old
            if(!empty($prod['featured_image']) && file_exists("../../" . $prod['featured_image'])) unlink("../../" . $prod['featured_image']);
            $featured_img_path = "assets/images/products/" . $new_name;
            $featured_sql = ", featured_image = '$featured_img_path'";
        }
    }

    // 2. Gallery Images Update (Append or Clear)
    // If 'clear_gallery' checkbox is checked, we empty it.
    // Then we append new ones. 
    // Usually complex, implementing simplistic append logic + clear option.
    $current_gallery = json_decode($prod['gallery_images'], true) ?? [];
    
    if(isset($_POST['clear_gallery']) && $_POST['clear_gallery'] == 1){
        foreach($current_gallery as $g_img) { if(file_exists("../../" . $g_img)) unlink("../../" . $g_img); }
        $current_gallery = [];
    }

    if(isset($_FILES['gallery_images'])){
        $target_dir = "../../assets/images/products/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        foreach($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name){
             if($_FILES['gallery_images']['error'][$key] == 0){
                $ext = strtolower(pathinfo($_FILES["gallery_images"]["name"][$key], PATHINFO_EXTENSION));
                $new_name = "prod_" . time() . "_gal_" . $key . "." . $ext;
                if(move_uploaded_file($tmp_name, $target_dir . $new_name)){
                    $current_gallery[] = "assets/images/products/gallery/" . $new_name;
                }
             }
        }
    }
    $gallery_json = json_encode($current_gallery);

    $sql = "UPDATE products SET 
        category_id=$category_id, name='$name', slug='$slug', description='$description', 
        video_url='$video_url', mrp=$mrp, sale_price=$sale_price, discount_percent=$discount_percent,
        seo_title='$seo_title', seo_description='$seo_description', seo_keywords='$seo_keywords', 
        schema_markup='$schema_markup', status='$status', gallery_images='$gallery_json', updated_at=NOW()
        $featured_sql
        WHERE id=$id";

    if ($conn->query($sql)) {
        $success_msg = "Product updated successfully!";
        // Refresh
        $prod = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$page_title = 'Edit Product';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Amadika Admin</title>
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
        .nav-tabs .nav-link.active { color: #D32F2F; border-bottom: 2px solid #D32F2F; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin: 5px; }
        .gallery-wrap { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Edit Product</h2>
                    <a href="manage-products.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data">
                        <ul class="nav nav-tabs mb-4" id="prodTabs" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button">General</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#media" type="button">Images & Video</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pricing" type="button">Pricing</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo" type="button">SEO & Schema</button></li>
                        </ul>

                        <div class="tab-content">
                            <!-- General -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($prod['name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category_id" required>
                                            <?php while($c = $cats->fetch_assoc()): ?>
                                                <option value="<?php echo $c['id']; ?>" <?php echo ($prod['category_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug" value="<?php echo htmlspecialchars($prod['slug']); ?>">
                                    </div>
                                     <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" <?php echo ($prod['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo ($prod['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="editor"><?php echo htmlspecialchars($prod['description']); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Media -->
                            <div class="tab-pane fade" id="media">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <?php if(!empty($prod['featured_image'])): ?>
                                        <div class="mb-2"><img src="../../<?php echo $prod['featured_image']; ?>" class="preview-img"></div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="featured_image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gallery Images</label>
                                    <?php 
                                        $gal = json_decode($prod['gallery_images'], true);
                                        if(!empty($gal)): 
                                    ?>
                                        <div class="gallery-wrap">
                                            <?php foreach($gal as $g): ?>
                                                <img src="../../<?php echo $g; ?>" class="preview-img">
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="clear_gallery" value="1" id="clearGal">
                                            <label class="form-check-label text-danger" for="clearGal">Delete Existing Gallery Images?</label>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">New images will be appended unless checked above.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">YouTube Video URL</label>
                                    <input type="url" class="form-control" name="video_url" value="<?php echo htmlspecialchars($prod['video_url']); ?>">
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="tab-pane fade" id="pricing">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">MRP</label>
                                        <input type="number" step="0.01" class="form-control" name="mrp" id="mrp" value="<?php echo $prod['mrp']; ?>" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sale Price</label>
                                        <input type="number" step="0.01" class="form-control" name="sale_price" id="sale_price" value="<?php echo $prod['sale_price']; ?>" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Discount %</label>
                                        <input type="text" class="form-control" id="discount_display" value="<?php echo $prod['discount_percent']; ?>%" readonly style="background-color: #f8f9fa;">
                                    </div>
                                </div>
                            </div>

                            <!-- SEO -->
                            <div class="tab-pane fade" id="seo">
                                <div class="mb-3">
                                    <label class="form-label">SEO Title</label>
                                    <input type="text" class="form-control" name="seo_title" value="<?php echo htmlspecialchars($prod['seo_title']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Description</label>
                                    <textarea class="form-control" name="seo_description" rows="2"><?php echo htmlspecialchars($prod['seo_description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords" value="<?php echo htmlspecialchars($prod['seo_keywords']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Schema Markup</label>
                                    <textarea class="form-control font-monospace" name="schema_markup" rows="8" style="font-size:0.85rem;"><?php echo htmlspecialchars($prod['schema_markup']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-warning px-4 text-white">Update Product</button>
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
        
        function calcDiscount() {
            let mrp = parseFloat(document.getElementById('mrp').value) || 0;
            let sale = parseFloat(document.getElementById('sale_price').value) || 0;
            if(mrp > 0 && sale > 0 && mrp > sale) {
                let disc = Math.round(((mrp - sale) / mrp) * 100);
                document.getElementById('discount_display').value = disc + '%';
            } else {
                document.getElementById('discount_display').value = '0%';
            }
        }
        
        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Updated!', text: '<?php echo $success_msg; ?>', confirmButtonColor: '#D32F2F' })
        .then(() => { window.location.href = 'manage-products.php'; });
        <?php endif; ?>
    </script>
</body>
</html>

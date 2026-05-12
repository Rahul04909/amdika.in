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

// Fetch Colors
$all_colors = $conn->query("SELECT * FROM colors ORDER BY name ASC");

// Fetch Existing Variants
$variants_res = $conn->query("SELECT * FROM product_color_variants WHERE product_id = $id");
$existing_variants = [];
while($v = $variants_res->fetch_assoc()) $existing_variants[] = $v;

$success_msg = '';
$error_msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn->begin_transaction();
        try {
            $name = $_POST['name'];
            $category_id = intval($_POST['category_id']);
            
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : $name;
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
            
            $chk = $conn->query("SELECT id FROM products WHERE slug = '$slug' AND id != $id");
            if($chk->num_rows > 0) $slug .= '-' . time();
    
            $description = $_POST['description'];
            $mrp = floatval($_POST['mrp']);
            $sale_price = floatval($_POST['sale_price']);
            $discount_percent = 0;
            if ($mrp > 0 && $sale_price > 0 && $mrp > $sale_price) {
                $discount_percent = round((($mrp - $sale_price) / $mrp) * 100);
            }
            
            $video_url = $_POST['video_url'];
            $status = $_POST['status'];
            $seo_title = $_POST['seo_title'];
            $seo_description = $_POST['seo_description'];
            $seo_keywords = $_POST['seo_keywords'];
            $schema_markup = !empty(trim($_POST['schema_markup'])) ? $_POST['schema_markup'] : null;

        // Featured Image Update
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $target_dir = "../../assets/images/products/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $ext = strtolower(pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION));
            $new_name = "prod_" . time() . "_feat." . $ext;
            if(move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_dir . $new_name)){
                if(!empty($prod['featured_image']) && file_exists("../../" . $prod['featured_image'])) unlink("../../" . $prod['featured_image']);
                $prod['featured_image'] = "assets/images/products/" . $new_name;
            }
        }

        // Gallery Images Update
        $current_gallery = json_decode($prod['gallery_images'], true) ?: [];
        if (isset($_POST['clear_gallery']) && $_POST['clear_gallery'] == 1) {
            foreach ($current_gallery as $g) {
                if (file_exists("../../" . $g)) unlink("../../" . $g);
            }
            $current_gallery = [];
        }

        if (isset($_FILES['gallery_images'])) {
            $target_dir = "../../assets/images/products/gallery/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['gallery_images']['error'][$key] == 0) {
                    $ext = strtolower(pathinfo($_FILES["gallery_images"]["name"][$key], PATHINFO_EXTENSION));
                    $new_name = "prod_" . time() . "_gal_" . $key . "." . $ext;
                    if (move_uploaded_file($tmp_name, $target_dir . $new_name)) {
                        $current_gallery[] = "assets/images/products/gallery/" . $new_name;
                    }
                }
            }
        }
        
        $gallery_json = json_encode($current_gallery);
        $gst_percent = intval($_POST['gst_percent']);

        $sql = "UPDATE products SET 
            category_id=?, name=?, slug=?, description=?, 
            video_url=?, mrp=?, sale_price=?, discount_percent=?, gst_percent=?,
            seo_title=?, seo_description=?, seo_keywords=?, 
            schema_markup=?, status=?, gallery_images=?, updated_at=NOW(), featured_image=?
            WHERE id=?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception($conn->error);

        $stmt->bind_param("issssssddiiissssi", 
            $category_id, $name, $slug, $description, $video_url, 
            $mrp, $sale_price, $discount_percent, $gst_percent,
            $seo_title, $seo_description, $seo_keywords, 
            $schema_markup, $status, $gallery_json, $prod['featured_image'], $id
        );

        if (!$stmt->execute()) throw new Exception($stmt->error);

        // 3. Handle Color Variants
        $submitted_variant_ids = [];
        if (isset($_POST['variant_color_id'])) {
            foreach ($_POST['variant_color_id'] as $index => $color_id) {
                if (empty($color_id)) continue;

                $v_id = isset($_POST['variant_id'][$index]) ? intval($_POST['variant_id'][$index]) : 0;
                $v_price = floatval($_POST['variant_price'][$index]);
                $v_image_path = isset($_POST['existing_variant_image'][$index]) ? $_POST['existing_variant_image'][$index] : '';

                // Handle Variant Image Upload
                if (isset($_FILES['variant_image']['tmp_name'][$index]) && $_FILES['variant_image']['error'][$index] == 0) {
                    $v_target_dir = "../../assets/images/products/variants/";
                    if (!file_exists($v_target_dir)) mkdir($v_target_dir, 0777, true);
                    $v_ext = strtolower(pathinfo($_FILES["variant_image"]["name"][$index], PATHINFO_EXTENSION));
                    $v_new_name = "var_" . $id . "_" . $index . "_" . time() . "." . $v_ext;
                    if(move_uploaded_file($_FILES["variant_image"]["tmp_name"][$index], $v_target_dir . $v_new_name)){
                        // Delete old variant image if exists
                        if(!empty($v_image_path) && file_exists("../../" . $v_image_path)) unlink("../../" . $v_image_path);
                        $v_image_path = "assets/images/products/variants/" . $v_new_name;
                    }
                }

                if ($v_id > 0) {
                    // Update
                    $stmt_v = $conn->prepare("UPDATE product_color_variants SET color_id=?, price=?, image_path=? WHERE id=? AND product_id=?");
                    $stmt_v->bind_param("idsii", $color_id, $v_price, $v_image_path, $v_id, $id);
                    $submitted_variant_ids[] = $v_id;
                } else {
                    // Insert
                    $stmt_v = $conn->prepare("INSERT INTO product_color_variants (product_id, color_id, price, image_path) VALUES (?, ?, ?, ?)");
                    $stmt_v->bind_param("iids", $id, $color_id, $v_price, $v_image_path);
                    if (!$stmt_v->execute()) throw new Exception($stmt_v->error);
                    $submitted_variant_ids[] = $conn->insert_id;
                }
            }
        }

        // Delete removed variants
        $existing_ids = array_column($existing_variants, 'id');
        $to_delete = array_diff($existing_ids, $submitted_variant_ids);
        foreach ($to_delete as $del_id) {
            // Unlink image first
            $del_row = $conn->query("SELECT image_path FROM product_color_variants WHERE id = $del_id")->fetch_assoc();
            if(!empty($del_row['image_path']) && file_exists("../../" . $del_row['image_path'])) unlink("../../" . $del_row['image_path']);
            $conn->query("DELETE FROM product_color_variants WHERE id = $del_id");
        }

        $conn->commit();
        $success_msg = "Product updated successfully!";
        // Refresh data
        $prod = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
        $variants_res = $conn->query("SELECT * FROM product_color_variants WHERE product_id = $id");
        $existing_variants = [];
        while($v = $variants_res->fetch_assoc()) $existing_variants[] = $v;

    } catch (Exception $e) {
        $conn->rollback();
        $error_msg = "Error: " . $e->getMessage();
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
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://cke4.ckeditor.com;">
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
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#variants" type="button">Colors & Variants</button></li>
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

                            <!-- Variants -->
                            <div class="tab-pane fade" id="variants">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Color-wise Variants</h5>
                                    <button type="button" class="btn btn-dark btn-sm" onclick="addVariantRow()">
                                        <i class="fas fa-plus me-1"></i> Add Color Variant
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="variantsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Select Color</th>
                                                <th width="200">Sale Price (Optional)</th>
                                                <th>Color Image</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($existing_variants as $index => $v): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="variant_id[]" value="<?php echo $v['id']; ?>">
                                                    <select class="form-select" name="variant_color_id[]" required>
                                                        <?php 
                                                            $all_colors->data_seek(0);
                                                            while($c = $all_colors->fetch_assoc()): 
                                                        ?>
                                                        <option value="<?php echo $c['id']; ?>" <?php echo ($v['color_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control" name="variant_price[]" value="<?php echo $v['price']; ?>">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <input type="hidden" name="existing_variant_image[]" value="<?php echo $v['image_path']; ?>">
                                                        <input type="file" class="form-control form-control-sm" name="variant_image[]" accept="image/*" onchange="previewVariantImage(this, <?php echo $index; ?>)">
                                                        <?php if(!empty($v['image_path'])): ?>
                                                            <img id="varPreview_<?php echo $index; ?>" src="../../<?php echo $v['image_path']; ?>" class="ms-2 rounded border" style="width:40px; height:40px; object-fit:cover;">
                                                        <?php else: ?>
                                                            <img id="varPreview_<?php echo $index; ?>" class="ms-2 rounded border" style="width:40px; height:40px; object-fit:cover; display:none;">
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="this.closest('tr').remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">If variant price is left 0 or empty, the general sale price will be used.</small>
                            </div>

                            <!-- Pricing -->
                            <div class="tab-pane fade" id="pricing">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">MRP</label>
                                        <input type="number" step="0.01" class="form-control" name="mrp" id="mrp" value="<?php echo $prod['mrp']; ?>" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Sale Price</label>
                                        <input type="number" step="0.01" class="form-control" name="sale_price" id="sale_price" value="<?php echo $prod['sale_price']; ?>" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">GST %</label>
                                        <input type="number" class="form-control" name="gst_percent" value="<?php echo isset($prod['gst_percent']) ? $prod['gst_percent'] : 18; ?>" min="0" max="100">
                                    </div>
                                    <div class="col-md-3 mb-3">
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

        // --- Variant Management ---
        const colors = <?php 
            $c_arr = [];
            $all_colors->data_seek(0);
            while($c = $all_colors->fetch_assoc()) $c_arr[] = $c;
            echo json_encode($c_arr); 
        ?>;

        function addVariantRow() {
            const tbody = document.querySelector('#variantsTable tbody');
            const rowCount = tbody.rows.length + Math.floor(Math.random() * 1000); // Unique index for new rows
            
            let colorOptions = '<option value="">Choose Color</option>';
            colors.forEach(c => {
                colorOptions += `<option value="${c.id}">${c.name}</option>`;
            });

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="hidden" name="variant_id[]" value="0">
                    <select class="form-select" name="variant_color_id[]" required>
                        ${colorOptions}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="variant_price[]" placeholder="0.00">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="existing_variant_image[]" value="">
                        <input type="file" class="form-control form-control-sm" name="variant_image[]" accept="image/*" onchange="previewVariantImage(this, ${rowCount})">
                        <img id="varPreview_${rowCount}" class="ms-2 rounded border" style="width:40px; height:40px; object-fit:cover; display:none;">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="this.closest('tr').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function previewVariantImage(input, index) {
            const preview = document.getElementById(`varPreview_${index}`);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Updated!', text: '<?php echo $success_msg; ?>', confirmButtonColor: '#D32F2F' })
        .then(() => { window.location.href = 'manage-products.php'; });
        <?php endif; ?>
    </script>
</body>
</html>

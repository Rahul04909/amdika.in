<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

// Fetch Categories for Dropdown
$cats = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC");

// Fetch Colors for Variants
$all_colors = $conn->query("SELECT * FROM colors ORDER BY name ASC");

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitization & Input (No real_escape_string needed for prepared statements)
    $name = $_POST['name'];
    $category_id = intval($_POST['category_id']);
    
    $slug = !empty($_POST['slug']) ? $_POST['slug'] : $name;
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug)));
    
    // Check for Duplicate Slug and append unique suffix if needed
    $chk = $conn->query("SELECT id FROM products WHERE slug = '$slug'");
    if($chk && $chk->num_rows > 0) {
        $slug .= '-' . time();
    }
    
    $description = $_POST['description'];
    $mrp = floatval($_POST['mrp']);
    $sale_price = floatval($_POST['sale_price']);
    // Auto Calc Discount if not provided (or re-calc to be safe)
    $discount_percent = 0;
    if ($mrp > 0 && $sale_price > 0 && $mrp > $sale_price) {
        $discount_percent = round((($mrp - $sale_price) / $mrp) * 100);
    }
    
    $video_url = $_POST['video_url'];
    $seo_title = $_POST['seo_title'];
    $seo_description = $_POST['seo_description'];
    $seo_keywords = $_POST['seo_keywords'];

    // Handle JSON field correctly: Empty string is not valid JSON, use NULL
    $schema_markup = !empty(trim($_POST['schema_markup'])) ? $_POST['schema_markup'] : null;
    
    // 1. Featured Image Upload
    $featured_img_path = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $target_dir = "../../assets/images/products/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $ext = strtolower(pathinfo($_FILES["featured_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "prod_" . time() . "_feat." . $ext;
        if(move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_dir . $new_name)){
            $featured_img_path = "assets/images/products/" . $new_name;
        }
    }

    // 2. Gallery Images Upload
    $gallery_paths = [];
    if(isset($_FILES['gallery_images'])){
        $target_dir = "../../assets/images/products/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        foreach($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name){
             if($_FILES['gallery_images']['error'][$key] == 0){
                $ext = strtolower(pathinfo($_FILES["gallery_images"]["name"][$key], PATHINFO_EXTENSION));
                $new_name = "prod_" . time() . "_gal_" . $key . "." . $ext;
                if(move_uploaded_file($tmp_name, $target_dir . $new_name)){
                    $gallery_paths[] = "assets/images/products/gallery/" . $new_name;
                }
             }
        }
    }
    $gallery_json = json_encode($gallery_paths);

    $gst_percent = intval($_POST['gst_percent']);

    $status = 'active';

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO products (
            category_id, name, slug, description, featured_image, gallery_images, video_url, 
            mrp, sale_price, discount_percent, gst_percent, seo_title, seo_description, seo_keywords, schema_markup, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception($conn->error);

        $stmt->bind_param("issssssddiiissss", 
            $category_id, $name, $slug, $description, $featured_img_path, $gallery_json, $video_url,
            $mrp, $sale_price, $discount_percent, $gst_percent, $seo_title, $seo_description, $seo_keywords, $schema_markup, $status
        );

        if (!$stmt->execute()) throw new Exception($stmt->error);
        $product_id = $conn->insert_id;

        // 3. Handle Color Variants
        if (isset($_POST['variant_color_id'])) {
            foreach ($_POST['variant_color_id'] as $index => $color_id) {
                if (empty($color_id)) continue;

                $v_price = floatval($_POST['variant_price'][$index]);
                $v_image_path = '';

                // Handle Variant Image Upload
                if (isset($_FILES['variant_image']['tmp_name'][$index]) && $_FILES['variant_image']['error'][$index] == 0) {
                    $v_target_dir = "../../assets/images/products/variants/";
                    if (!file_exists($v_target_dir)) mkdir($v_target_dir, 0777, true);
                    $v_ext = strtolower(pathinfo($_FILES["variant_image"]["name"][$index], PATHINFO_EXTENSION));
                    $v_new_name = "var_" . $product_id . "_" . $index . "_" . time() . "." . $v_ext;
                    if(move_uploaded_file($_FILES["variant_image"]["tmp_name"][$index], $v_target_dir . $v_new_name)){
                        $v_image_path = "assets/images/products/variants/" . $v_new_name;
                    }
                }

                // Handle Variant Gallery Uploads
                $v_gallery = [];
                if (isset($_FILES['variant_gallery']['tmp_name'][$index])) {
                    $v_gal_dir = "../../assets/images/products/variants/gallery/";
                    if (!file_exists($v_gal_dir)) mkdir($v_gal_dir, 0777, true);
                    
                    foreach ($_FILES['variant_gallery']['tmp_name'][$index] as $k => $tmp_name) {
                        if ($_FILES['variant_gallery']['error'][$index][$k] == 0) {
                            $ext = strtolower(pathinfo($_FILES["variant_gallery"]["name"][$index][$k], PATHINFO_EXTENSION));
                            $new_name = "var_gal_" . $product_id . "_" . $index . "_" . $k . "_" . time() . "." . $ext;
                            if (move_uploaded_file($tmp_name, $v_gal_dir . $new_name)) {
                                $v_gallery[] = "assets/images/products/variants/gallery/" . $new_name;
                            }
                        }
                    }
                }
                $v_gallery_json = json_encode($v_gallery);

                $stmt_v = $conn->prepare("INSERT INTO product_color_variants (product_id, color_id, price, image_path, gallery_images) VALUES (?, ?, ?, ?, ?)");
                $stmt_v->bind_param("iidss", $product_id, $color_id, $v_price, $v_image_path, $v_gallery_json);
                if (!$stmt_v->execute()) throw new Exception($stmt_v->error);
            }
        }

        $conn->commit();
        $success_msg = "Product and variants added successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $error_msg = "Error: " . $e->getMessage();
    }
}

$page_title = 'Add New Product';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Amadika Admin</title>
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
        .nav-tabs .nav-link { color: #636E72; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #D32F2F; border-bottom: 2px solid #D32F2F; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin: 5px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>
            
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Add New Product</h2>
                    <a href="manage-products.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data" id="addProductForm">
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
                                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="prodName" required onkeyup="generateSlug(this.value); updateSchema();">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php while($c = $cats->fetch_assoc()): ?>
                                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug" id="slug">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="editor"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Media -->
                            <div class="tab-pane fade" id="media">
                                <div class="mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <input type="file" class="form-control" name="featured_image" accept="image/*" onchange="previewImage(this, 'featPreview')">
                                    <img id="featPreview" class="preview-img mt-2" style="display:none;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gallery Images (Multiple)</label>
                                    <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">Select multiple images to create a gallery.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">YouTube Video URL</label>
                                    <input type="url" class="form-control" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
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
                                                <th width="150">Price</th>
                                                <th>Main Image</th>
                                                <th>Gallery Images</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic Rows -->
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">If variant price is left 0 or empty, the general sale price will be used.</small>
                            </div>

                            <!-- Pricing -->
                            <div class="tab-pane fade" id="pricing">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">MRP (Original Price)</label>
                                        <input type="number" step="0.01" class="form-control" name="mrp" id="mrp" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sale Price</label>
                                        <input type="number" step="0.01" class="form-control" name="sale_price" id="sale_price" oninput="calcDiscount()">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">GST Percentage (%)</label>
                                        <input type="number" class="form-control" name="gst_percent" value="18" min="0" max="100">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label">Discount Percentage</label>
                                        <input type="text" class="form-control" id="discount_display" readonly style="background-color: #f8f9fa; color: #198754; font-weight: bold;">
                                    </div>
                                </div>
                            </div>

                            <!-- SEO & Schema -->
                            <div class="tab-pane fade" id="seo">
                                <div class="mb-3">
                                    <label class="form-label">SEO Meta Title</label>
                                    <input type="text" class="form-control" name="seo_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Meta Description</label>
                                    <textarea class="form-control" name="seo_description" rows="2" onkeyup="updateSchema()"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Keywords</label>
                                    <input type="text" class="form-control" name="seo_keywords">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Schema Markup (Auto-Generated JSON-LD)</label>
                                    <textarea class="form-control font-monospace" name="schema_markup" id="schema_markup" rows="8" style="font-size: 0.85rem; color: #d63384;"></textarea>
                                    <small class="text-muted">You can verify this at <a href="https://validator.schema.org/" target="_blank">Schema Validator</a>.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4">Publish Product</button>
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
        if(document.getElementById('editor')) CKEDITOR.replace('editor');

        // Discount Calc
        function calcDiscount() {
            let mrp = parseFloat(document.getElementById('mrp').value) || 0;
            let sale = parseFloat(document.getElementById('sale_price').value) || 0;
            if(mrp > 0 && sale > 0 && mrp > sale) {
                let disc = Math.round(((mrp - sale) / mrp) * 100);
                document.getElementById('discount_display').value = disc + '%';
            } else {
                document.getElementById('discount_display').value = '0%';
            }
            updateSchema();
        }

        // Slug Gen
        function generateSlug(text) {
           let slug = text.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
           document.getElementById('slug').value = slug;
        }

        // Schema Generator
        function updateSchema() {
            let name = document.getElementById('prodName').value || 'Product Name';
            let desc = document.getElementsByName('seo_description')[0].value || 'Product Description';
            let price = document.getElementById('sale_price').value || '0.00';
            let currency = 'INR';
            
            let schema = {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": name,
                "description": desc,
                "sku": "SKU-" + Math.floor(Math.random() * 10000), 
                "offers": {
                    "@type": "Offer",
                    "url": window.location.origin + "/product/" + document.getElementById('slug').value,
                    "priceCurrency": currency,
                    "price": price,
                    "priceValidUntil": "<?php echo date('Y-m-d', strtotime('+1 year')); ?>",
                    "availability": "https://schema.org/InStock"
                },
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "4.5",
                    "reviewCount": "12" 
                }
            };
            document.getElementById('schema_markup').value = JSON.stringify(schema, null, 2);
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

        // --- Variant Management ---
        const colors = <?php 
            $c_arr = [];
            $all_colors->data_seek(0);
            while($c = $all_colors->fetch_assoc()) $c_arr[] = $c;
            echo json_encode($c_arr); 
        ?>;

        function addVariantRow() {
            const tbody = document.querySelector('#variantsTable tbody');
            const rowCount = tbody.rows.length;
            
            let colorOptions = '<option value="">Choose Color</option>';
            colors.forEach(c => {
                colorOptions += `<option value="${c.id}">${c.name}</option>`;
            });

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select" name="variant_color_id[]" required>
                        ${colorOptions}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="variant_price[]" placeholder="0.00">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <input type="file" class="form-control form-control-sm" name="variant_image[]" accept="image/*" onchange="previewVariantImage(this, ${rowCount})">
                        <img id="varPreview_${rowCount}" class="ms-2 rounded border" style="width:35px; height:35px; object-fit:cover; display:none;">
                    </div>
                </td>
                <td>
                    <input type="file" class="form-control form-control-sm" name="variant_gallery[${rowCount}][]" multiple accept="image/*">
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

        // Initialize with one row if needed
        // addVariantRow(); 

        <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>', confirmButtonColor: '#D32F2F' })
        .then(() => { window.location.href = 'manage-products.php'; });
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

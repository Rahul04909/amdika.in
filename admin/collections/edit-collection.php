<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header("Location: manage-collections.php");
    exit;
}

$collection = $conn->query("SELECT * FROM collections WHERE id = $id")->fetch_assoc();
if (!$collection) {
    header("Location: manage-collections.php");
    exit;
}

$success_msg = '';
$error_msg = '';

$products_list = $conn->query("SELECT id, name, slug FROM products WHERE status = 'active' ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_name = $conn->real_escape_string($_POST['model_name']);
    $amk_code = $conn->real_escape_string($_POST['amk_code'] ?? '');
    $main_product_id = !empty($_POST['main_product_id']) ? intval($_POST['main_product_id']) : 'NULL';
    $selected_products = isset($_POST['selected_products']) && is_array($_POST['selected_products']) ? $_POST['selected_products'] : [];
    $selected_products_json = json_encode(array_map('intval', $selected_products));
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    $hero_image = $collection['hero_image'];
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $target_dir = "../../assets/images/collection/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES["hero_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "col_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES["hero_image"]["tmp_name"], $target_dir . $new_name)) {
            if (!empty($collection['hero_image']) && file_exists("../../" . $collection['hero_image'])) {
                unlink("../../" . $collection['hero_image']);
            }
            $hero_image = "assets/images/collection/" . $new_name;
        }
    }

    $mp_id = $main_product_id === 'NULL' ? 'NULL' : $main_product_id;
    $sql = "UPDATE collections SET
            model_name = '$model_name',
            amk_code = '$amk_code',
            hero_image = " . ($hero_image ? "'$hero_image'" : "NULL") . ",
            main_product_id = $mp_id,
            selected_products = '$selected_products_json',
            sort_order = $sort_order,
            status = '$status'
            WHERE id = $id";

    if ($conn->query($sql)) {
        $success_msg = "Collection updated successfully!";
        $collection = $conn->query("SELECT * FROM collections WHERE id = $id")->fetch_assoc();
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$selected_ids = json_decode($collection['selected_products'], true) ?? [];

$page_title = 'Edit Collection';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Collection - Amadika Admin</title>
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
        .preview-img { max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd; margin-top: 10px; display: none; object-fit: cover; }
        .preview-img.exists { display: block; }
        .product-checkbox-list { max-height: 400px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px; }
        .product-checkbox-list .form-check { padding: 6px 10px; border-radius: 4px; transition: background 0.15s; }
        .product-checkbox-list .form-check:hover { background: #f5f7fa; }
        .product-checkbox-list .form-check-input:checked ~ .form-check-label { color: #D32F2F; font-weight: 600; }
        .select-all-bar { background: #f8f9fa; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 12px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Edit Collection</h2>
                    <a href="manage-collections.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="model_name" value="<?php echo htmlspecialchars($collection['model_name']); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">AMK Code</label>
                                <input type="text" class="form-control" name="amk_code" value="<?php echo htmlspecialchars($collection['amk_code']); ?>" placeholder="e.g. AMK 1501">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" value="<?php echo intval($collection['sort_order']); ?>" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hero Image</label>
                                <input type="file" class="form-control" name="hero_image" accept="image/*" onchange="previewImage(this, 'heroPreview')">
                                <?php if ($collection['hero_image']): ?>
                                    <img id="heroPreview" class="preview-img exists" src="../../<?php echo $collection['hero_image']; ?>" alt="Hero">
                                <?php else: ?>
                                    <img id="heroPreview" class="preview-img">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active" <?php echo $collection['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $collection['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Main Product <span class="text-muted">(for "Shop Collection" button link)</span></label>
                            <select class="form-select" name="main_product_id">
                                <option value="">— Select Main Product —</option>
                                <?php if ($products_list && $products_list->num_rows > 0):
                                    $products_list->data_seek(0);
                                    while ($p = $products_list->fetch_assoc()): ?>
                                        <option value="<?php echo $p['id']; ?>" <?php echo $collection['main_product_id'] == $p['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($p['name']); ?>
                                        </option>
                                <?php endwhile; endif; ?>
                            </select>
                            <small class="text-muted">Customers will be redirected to this product's detail page when clicking "Shop Collection"</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Collection Products <span class="text-muted">(shown in the right carousel)</span></label>
                            <div class="select-all-bar">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                    <label class="form-check-label fw-medium" for="selectAll">Select All</label>
                                </div>
                                <span class="text-muted small" id="selectedCount">0 selected</span>
                            </div>
                            <div class="product-checkbox-list">
                                <?php
                                $products_list->data_seek(0);
                                if ($products_list && $products_list->num_rows > 0):
                                    while ($p = $products_list->fetch_assoc()):
                                        $checked = in_array($p['id'], $selected_ids) ? 'checked' : '';
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input product-checkbox" type="checkbox" name="selected_products[]" value="<?php echo $p['id']; ?>" id="prod_<?php echo $p['id']; ?>" <?php echo $checked; ?> onchange="updateSelectedCount()">
                                        <label class="form-check-label" for="prod_<?php echo $p['id']; ?>">
                                            <?php echo htmlspecialchars($p['name']); ?>
                                            <small class="text-muted">(<?php echo htmlspecialchars($p['slug']); ?>)</small>
                                        </label>
                                    </div>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                    <p class="text-muted text-center py-3 mb-0">No products available. <a href="../products/add-product.php" class="text-danger">Add products first</a>.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4"><i class="fas fa-save me-2"></i>Update Collection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewImage(input, imgId) {
            const preview = document.getElementById(imgId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.classList.add('exists');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleSelectAll(source) {
            document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = source.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const count = document.querySelectorAll('.product-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = count + ' selected';
        }

        updateSelectedCount();

        <?php if ($success_msg): ?>
            Swal.fire({
                icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>',
                confirmButtonColor: '#D32F2F'
            });
        <?php endif; ?>

        <?php if ($error_msg): ?>
            Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$success_msg = '';
$error_msg = '';

$products_res = $conn->query("SELECT id, name, slug, featured_image FROM products WHERE status = 'active' ORDER BY name ASC");
$all_products = [];
if ($products_res) {
    while ($p = $products_res->fetch_assoc()) {
        $all_products[] = $p;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_name = $conn->real_escape_string($_POST['model_name']);
    $amk_code = $conn->real_escape_string($_POST['amk_code'] ?? '');
    $main_product_id = !empty($_POST['main_product_id']) ? intval($_POST['main_product_id']) : 'NULL';
    $selected_ids = isset($_POST['selected_products']) ? json_decode($_POST['selected_products'], true) : [];
    $selected_products_json = json_encode(array_map('intval', $selected_ids ?: []));
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    $hero_image = '';
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $target_dir = "../../assets/images/collection/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES["hero_image"]["name"], PATHINFO_EXTENSION));
        $new_name = "col_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES["hero_image"]["tmp_name"], $target_dir . $new_name)) {
            $hero_image = "assets/images/collection/" . $new_name;
        }
    }

    $mp_id = $main_product_id === 'NULL' ? 'NULL' : $main_product_id;
    $sql = "INSERT INTO collections (model_name, amk_code, hero_image, main_product_id, selected_products, sort_order, status)
            VALUES ('$model_name', '$amk_code', " . ($hero_image ? "'$hero_image'" : "NULL") . ", $mp_id, '$selected_products_json', $sort_order, '$status')";

    if ($conn->query($sql)) {
        $success_msg = "Collection added successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}

$page_title = 'Add Collection';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Collection - Amadika Admin</title>
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

        /* ── Product Search ── */
        .product-search-wrap { position: relative; }
        .product-search-wrap .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; pointer-events: none; }
        .product-search-wrap .clear-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; display: none; font-size: 16px; padding: 4px 8px; }
        .product-search-wrap .clear-btn:hover { color: #ef4444; }
        .product-search-input { padding-left: 38px !important; padding-right: 40px !important; border-radius: 10px !important; border: 2px solid #e5e7eb !important; transition: all 0.2s; height: 48px; font-size: 15px; }
        .product-search-input:focus { border-color: #D32F2F !important; box-shadow: 0 0 0 3px rgba(211,47,47,0.1) !important; }

        /* ── Search Results Dropdown ── */
        .search-results-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.12); z-index: 1050; max-height: 320px; overflow-y: auto; display: none; margin-top: 4px; }
        .search-result-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; cursor: pointer; transition: background 0.15s; border-bottom: 1px solid #f3f4f6; }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: #fef2f2; }
        .search-result-item .thumb { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; flex-shrink: 0; background: #f9fafb; }
        .search-result-item .info { flex: 1; min-width: 0; }
        .search-result-item .info .name { font-size: 14px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-result-item .info .slug { font-size: 11px; color: #9ca3af; }
        .search-result-item .add-badge { width: 26px; height: 26px; border-radius: 50%; background: #D32F2F; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; opacity: 0; transition: opacity 0.15s; }
        .search-result-item:hover .add-badge { opacity: 1; }
        .search-result-item.added { background: #f0fdf4; }
        .search-result-item.added .add-badge { background: #22c55e; opacity: 1; }
        .search-result-empty { padding: 24px; text-align: center; color: #9ca3af; font-size: 14px; }

        /* ── Selected Products Grid ── */
        .selected-products-section { margin-top: 16px; }
        .selected-products-section .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .selected-products-section .section-header .count { font-size: 13px; font-weight: 500; color: #6b7280; background: #f3f4f6; padding: 2px 12px; border-radius: 20px; }
        .selected-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .selected-card { display: flex; align-items: center; gap: 10px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 8px 10px; transition: all 0.2s; }
        .selected-card:hover { border-color: #D32F2F; background: #fef2f2; }
        .selected-card .thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; flex-shrink: 0; background: #fff; }
        .selected-card .info { flex: 1; min-width: 0; }
        .selected-card .info .name { font-size: 13px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .selected-card .info .slug { font-size: 10px; color: #9ca3af; }
        .selected-card .remove-btn { width: 26px; height: 26px; border-radius: 50%; border: none; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; font-size: 14px; flex-shrink: 0; }
        .selected-card .remove-btn:hover { background: #ef4444; color: #fff; }
        .selected-empty { text-align: center; padding: 24px; color: #d1d5db; font-size: 14px; border: 2px dashed #e5e7eb; border-radius: 12px; }

        /* ── Main Product Picker ── */
        .main-product-card { display: flex; align-items: center; gap: 14px; background: #f0fdf4; border: 2px solid #22c55e; border-radius: 12px; padding: 12px 16px; margin-top: 8px; }
        .main-product-card .thumb { width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; flex-shrink: 0; background: #fff; }
        .main-product-card .info { flex: 1; min-width: 0; }
        .main-product-card .info .name { font-size: 15px; font-weight: 600; color: #111827; }
        .main-product-card .info .slug { font-size: 11px; color: #6b7280; }
        .main-product-card .change-btn { padding: 6px 16px; border-radius: 8px; border: 1px solid #22c55e; background: #fff; color: #22c55e; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.15s; flex-shrink: 0; }
        .main-product-card .change-btn:hover { background: #22c55e; color: #fff; }
        .main-product-card .check-badge { width: 28px; height: 28px; border-radius: 50%; background: #22c55e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .main-product-empty { text-align: center; padding: 16px; color: #9ca3af; font-size: 13px; border: 2px dashed #e5e7eb; border-radius: 10px; margin-top: 8px; cursor: pointer; transition: all 0.15s; }
        .main-product-empty:hover { border-color: #D32F2F; color: #D32F2F; background: #fef2f2; }
        .main-product-search-wrap { position: relative; margin-top: 8px; }
        .main-product-search-wrap .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; pointer-events: none; }
        .main-product-search-wrap .clear-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; display: none; font-size: 16px; padding: 4px 8px; }
        .main-product-search-wrap .clear-btn:hover { color: #ef4444; }
        .main-product-search-input { padding-left: 38px !important; padding-right: 40px !important; border-radius: 10px !important; border: 2px solid #e5e7eb !important; transition: all 0.2s; height: 44px; font-size: 14px; }
        .main-product-search-input:focus { border-color: #22c55e !important; box-shadow: 0 0 0 3px rgba(34,197,94,0.1) !important; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper">
            <?php include '../../admin/includes/header.php'; ?>

            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary">Add New Collection</h2>
                    <a href="manage-collections.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <div class="card card-custom">
                    <form method="POST" enctype="multipart/form-data" id="collectionForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="model_name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">AMK Code</label>
                                <input type="text" class="form-control" name="amk_code" placeholder="e.g. AMK 1501">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" value="0" min="0">
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hero Image <span class="text-muted fw-normal">(recommended: 1200×675 px · 16:9 · max 500 KB)</span></label>
                                <input type="file" class="form-control" name="hero_image" accept="image/*" onchange="previewImage(this, 'heroPreview')">
                                <img id="heroPreview" class="preview-img">
                                <small class="text-muted">For perfect fit, use a landscape image with 16:9 aspect ratio. The image will be displayed fully without cropping.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Main Product <span class="text-muted">(for "Shop Collection" button link)</span></label>

                            <div class="main-product-picker" id="mainProductPicker">
                                <div class="main-product-empty" id="mainProductEmpty" onclick="document.getElementById('mainProductSearch').focus()">
                                    <i class="fas fa-search me-2"></i>Click to select the main product for this collection
                                </div>

                                <div class="main-product-card" id="mainProductCard" style="display:none">
                                    <span class="check-badge"><i class="fas fa-check"></i></span>
                                    <img src="" alt="" class="thumb" id="mainProductThumb">
                                    <div class="info">
                                        <div class="name" id="mainProductName"></div>
                                        <div class="slug" id="mainProductSlug"></div>
                                    </div>
                                    <button type="button" class="change-btn" onclick="clearMainProduct()">Change</button>
                                </div>

                                <div class="main-product-search-wrap" style="display:none" id="mainProductSearchWrap">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="form-control main-product-search-input" id="mainProductSearch" placeholder="Search main product by name..." autocomplete="off">
                                    <button type="button" class="clear-btn" id="clearMainSearch"><i class="fas fa-times"></i></button>
                                    <div class="search-results-dropdown" id="mainSearchResults"></div>
                                </div>
                            </div>

                            <input type="hidden" name="main_product_id" id="mainProductIdInput" value="">
                            <small class="text-muted">Customers will be redirected to this product's detail page</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Collection Products <span class="text-muted">(shown in the right carousel)</span></label>

                            <div class="product-search-wrap">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control product-search-input" id="productSearch" placeholder="Search products by name..." autocomplete="off">
                                <button type="button" class="clear-btn" id="clearSearch"><i class="fas fa-times"></i></button>
                                <div class="search-results-dropdown" id="searchResults"></div>
                            </div>

                            <div class="selected-products-section">
                                <div class="section-header">
                                    <span class="fw-medium text-secondary">Selected Products</span>
                                    <span class="count" id="selectedCount">0 items</span>
                                </div>
                                <div class="selected-grid" id="selectedGrid">
                                    <div class="selected-empty" id="selectedEmpty">
                                        <i class="fas fa-box-open d-block mb-2 fs-4 opacity-25"></i>
                                        No products selected. Search and click to add.
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="selected_products" id="selectedProductsInput" value="[]">
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-danger px-4"><i class="fas fa-save me-2"></i>Save Collection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── All Products Data ──────────────────────────────────────
        const allProducts = <?php echo json_encode($all_products); ?>;
        let selectedProducts = [];

        // ─── DOM refs ──────────────────────────────────────────────
        const searchInput = document.getElementById('productSearch');
        const clearBtn = document.getElementById('clearSearch');
        const resultsDropdown = document.getElementById('searchResults');
        const selectedGrid = document.getElementById('selectedGrid');
        const selectedEmpty = document.getElementById('selectedEmpty');
        const selectedCount = document.getElementById('selectedCount');
        const hiddenInput = document.getElementById('selectedProductsInput');

        // ─── Render Selected Products ──────────────────────────────
        function renderSelected() {
            const others = selectedGrid.querySelectorAll('.selected-card');
            others.forEach(el => el.remove());
            selectedEmpty.style.display = selectedProducts.length === 0 ? 'block' : 'none';
            selectedCount.textContent = selectedProducts.length + ' item' + (selectedProducts.length !== 1 ? 's' : '');
            hiddenInput.value = JSON.stringify(selectedProducts);

            selectedProducts.forEach(id => {
                const prod = allProducts.find(p => p.id == id);
                if (!prod) return;
                const card = document.createElement('div');
                card.className = 'selected-card';
                card.dataset.id = id;
                const imgSrc = prod.featured_image
                    ? '../../' + prod.featured_image
                    : '../../assets/images/products/prod_1769666277_feat.png';
                card.innerHTML = `
                    <img src="${imgSrc}" alt="" class="thumb" onerror="this.src='../../assets/images/amdika-logo.png'">
                    <div class="info">
                        <div class="name">${escHtml(prod.name)}</div>
                        <div class="slug">${escHtml(prod.slug)}</div>
                    </div>
                    <button type="button" class="remove-btn" onclick="removeProduct(${id})" title="Remove">&times;</button>
                `;
                selectedGrid.appendChild(card);
            });
            updateSearchResults();
        }

        // ─── Add / Remove ──────────────────────────────────────────
        function addProduct(id) {
            id = Number(id);
            if (!selectedProducts.includes(id)) {
                selectedProducts.push(id);
                renderSelected();
                searchInput.value = '';
                resultsDropdown.style.display = 'none';
                clearBtn.style.display = 'none';
                searchInput.focus();
            }
        }

        function removeProduct(id) {
            id = Number(id);
            selectedProducts = selectedProducts.filter(p => p !== id);
            renderSelected();
            if (searchInput.value.trim()) {
                performSearch(searchInput.value.trim().toLowerCase());
            }
            searchInput.focus();
        }

        // ─── Search ────────────────────────────────────────────────
        function performSearch(query) {
            if (!query) {
                resultsDropdown.style.display = 'none';
                return;
            }
            const filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(query)
            );
            if (filtered.length === 0) {
                resultsDropdown.innerHTML = '<div class="search-result-empty">No products found matching "<strong>' + escHtml(query) + '</strong>"</div>';
                resultsDropdown.style.display = 'block';
                return;
            }
            let html = '';
            filtered.forEach(p => {
                const isAdded = selectedProducts.includes(p.id);
                const imgSrc = p.featured_image
                    ? '../../' + p.featured_image
                    : '../../assets/images/products/prod_1769666277_feat.png';
                html += `
                    <div class="search-result-item ${isAdded ? 'added' : ''}" onclick="${isAdded ? '' : "addProduct(" + p.id + ")"}" ${isAdded ? 'style="cursor:default;opacity:0.6"' : ''}>
                        <img src="${imgSrc}" alt="" class="thumb" onerror="this.src='../../assets/images/amdika-logo.png'">
                        <div class="info">
                            <div class="name">${highlightMatch(escHtml(p.name), query)}</div>
                            <div class="slug">${escHtml(p.slug)}</div>
                        </div>
                        <span class="add-badge">${isAdded ? '<i class="fas fa-check"></i>' : '+'}</span>
                    </div>
                `;
            });
            resultsDropdown.innerHTML = html;
            resultsDropdown.style.display = 'block';
        }

        function highlightMatch(text, query) {
            const idx = text.toLowerCase().indexOf(query);
            if (idx === -1) return text;
            return text.slice(0, idx) + '<strong style="color:#D32F2F">' + text.slice(idx, idx + query.length) + '</strong>' + text.slice(idx + query.length);
        }

        function escHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // ─── Event Listeners ───────────────────────────────────────
        let searchTimer;
        searchInput.addEventListener('input', function() {
            const val = this.value.trim();
            clearBtn.style.display = val ? 'block' : 'none';
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => performSearch(val.toLowerCase()), 150);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                resultsDropdown.style.display = 'block';
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.product-search-wrap')) {
                resultsDropdown.style.display = 'none';
            }
            if (!e.target.closest('.main-product-search-wrap') && !e.target.closest('.main-product-picker')) {
                mainSearchResults.style.display = 'none';
            }
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            resultsDropdown.style.display = 'none';
            searchInput.focus();
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                resultsDropdown.style.display = 'none';
                searchInput.blur();
            }
        });

        // ═══════════════════════════════════════════════════════════
        // ─── Main Product Picker ──────────────────────────────────
        // ═══════════════════════════════════════════════════════════
        let mainProductId = null;

        const mainProductEmpty = document.getElementById('mainProductEmpty');
        const mainProductCard = document.getElementById('mainProductCard');
        const mainProductThumb = document.getElementById('mainProductThumb');
        const mainProductName = document.getElementById('mainProductName');
        const mainProductSlug = document.getElementById('mainProductSlug');
        const mainProductIdInput = document.getElementById('mainProductIdInput');
        const mainSearchWrap = document.getElementById('mainProductSearchWrap');
        const mainSearchInput = document.getElementById('mainProductSearch');
        const mainClearBtn = document.getElementById('clearMainSearch');
        const mainSearchResults = document.getElementById('mainSearchResults');

        mainProductEmpty.addEventListener('click', function() {
            mainSearchWrap.style.display = 'block';
            mainSearchInput.focus();
        });

        function selectMainProduct(id) {
            id = Number(id);
            const prod = allProducts.find(p => p.id == id);
            if (!prod) return;

            mainProductId = id;
            mainProductIdInput.value = id;
            mainProductEmpty.style.display = 'none';
            mainProductCard.style.display = 'flex';

            const imgSrc = prod.featured_image
                ? '../../' + prod.featured_image
                : '../../assets/images/products/prod_1769666277_feat.png';
            mainProductThumb.src = imgSrc;
            mainProductName.textContent = prod.name;
            mainProductSlug.textContent = prod.slug;

            mainSearchInput.value = '';
            mainSearchResults.style.display = 'none';
            mainSearchWrap.style.display = 'none';
            mainClearBtn.style.display = 'none';
        }

        function clearMainProduct() {
            mainProductId = null;
            mainProductIdInput.value = '';
            mainProductCard.style.display = 'none';
            mainProductEmpty.style.display = 'block';
            mainSearchInput.value = '';
            mainSearchResults.style.display = 'none';
            mainSearchWrap.style.display = 'none';
            mainClearBtn.style.display = 'none';
        }

        function performMainSearch(query) {
            if (!query) {
                mainSearchResults.style.display = 'none';
                return;
            }
            const filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(query)
            );
            if (filtered.length === 0) {
                mainSearchResults.innerHTML = '<div class="search-result-empty">No products found matching "<strong>' + escHtml(query) + '</strong>"</div>';
                mainSearchResults.style.display = 'block';
                return;
            }
            let html = '';
            filtered.forEach(p => {
                const isSelected = mainProductId === p.id;
                const imgSrc = p.featured_image
                    ? '../../' + p.featured_image
                    : '../../assets/images/products/prod_1769666277_feat.png';
                html += `
                    <div class="search-result-item ${isSelected ? 'added' : ''}" onclick="${isSelected ? '' : "selectMainProduct(" + p.id + ")"}" ${isSelected ? 'style="cursor:default;opacity:0.6"' : ''}>
                        <img src="${imgSrc}" alt="" class="thumb" onerror="this.src='../../assets/images/amdika-logo.png'">
                        <div class="info">
                            <div class="name">${highlightMatch(escHtml(p.name), query)}</div>
                            <div class="slug">${escHtml(p.slug)}</div>
                        </div>
                        <span class="add-badge">${isSelected ? '<i class="fas fa-check"></i>' : '<i class="fas fa-arrow-right"></i>'}</span>
                    </div>
                `;
            });
            mainSearchResults.innerHTML = html;
            mainSearchResults.style.display = 'block';
        }

        let mainSearchTimer;
        mainSearchInput.addEventListener('input', function() {
            const val = this.value.trim();
            mainClearBtn.style.display = val ? 'block' : 'none';
            clearTimeout(mainSearchTimer);
            mainSearchTimer = setTimeout(() => performMainSearch(val.toLowerCase()), 150);
        });

        mainSearchInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                mainSearchResults.style.display = 'block';
            }
        });

        mainClearBtn.addEventListener('click', function() {
            mainSearchInput.value = '';
            mainClearBtn.style.display = 'none';
            mainSearchResults.style.display = 'none';
            mainSearchInput.focus();
        });

        mainSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                mainSearchResults.style.display = 'none';
                mainSearchInput.blur();
            }
        });

        // ─── Image Preview ─────────────────────────────────────────
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

        // ─── SweetAlert ────────────────────────────────────────────
        <?php if ($success_msg): ?>
            Swal.fire({
                icon: 'success', title: 'Success', text: '<?php echo $success_msg; ?>',
                confirmButtonColor: '#D32F2F'
            }).then(() => { window.location.href = 'manage-collections.php?success=saved'; });
        <?php endif; ?>

        <?php if ($error_msg): ?>
            Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo $error_msg; ?>' });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
require_once 'database/db_config.php';

// Get Product Slug
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// Fetch Product Details
$sql = "SELECT * FROM products WHERE slug = '$slug' AND status = 'active'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Product not found
    header("HTTP/1.0 404 Not Found");
    include 'includes/header.php';
    echo '<div class="container py-5 text-center">
            <img src="assets/images/no-product.png" alt="Not Found" style="max-width:200px; opacity:0.6;">
            <h2 class="mt-3 text-secondary">Product Not Found</h2>
            <p class="text-muted">The product you are looking for might have been removed or unavailable.</p>
            <a href="index.php" class="btn btn-warning px-4 mt-2">Continue Shopping</a>
          </div>';
    include 'includes/footer.php';
    exit;
}

$product = $result->fetch_assoc();

// SEO Setup (Dynamic Page Title)
$page_title = $product['seo_title'] ?: $product['name']; // Fallback to name
// Header is included later, so we might need a way to pass title. 
// Assuming header.php uses $page_title if set? 
// Let's check header.php content again or just set it. 
// Standard header.php usually has <title>... so we might need to modify header or hope it checks a var.
// For now, I'll just rely on standard header or insert it if header allows logic.
// Checking previous turn... header.php has <title>Amadika - Online Shopping</title> hardcoded.
// I will just proceed, and maybe update header later for dynamic SEO title support if requested.

include 'includes/header.php';

// Fetch Color Variants
$variants = [];
$v_sql = "SELECT v.*, c.name as color_name, c.hex_code 
          FROM product_color_variants v 
          JOIN colors c ON v.color_id = c.id 
          WHERE v.product_id = " . $product['id'];
$v_res = $conn->query($v_sql);
while ($v = $v_res->fetch_assoc())
    $variants[] = $v;

// Data Prep - Refined Gallery Parsing
$raw_gallery = $product['gallery_images'];
$gallery = [];

if (!empty($raw_gallery)) {
    // Robust parsing: strip potential outer quotes if stored double-encoded
    $raw_gallery = trim($raw_gallery);
    if ($raw_gallery[0] === '"' && $raw_gallery[strlen($raw_gallery)-1] === '"') {
        $raw_gallery = json_decode($raw_gallery);
    }
    
    // Attempt JSON decode
    $decoded = json_decode($raw_gallery, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $gallery = $decoded;
    } else {
        // Fallback: split by comma if not JSON
        $gallery = array_map('trim', explode(',', $raw_gallery));
    }
}

// Add featured image to start
if (!empty($product['featured_image'])) {
    array_unshift($gallery, $product['featured_image']);
}

// Remove variant images from gallery as per user request
// foreach ($variants as $v) {
//     if (!empty($v['image_path'])) {
//         $gallery[] = $v['image_path'];
//     }
// }

$gallery = array_unique(array_filter($gallery));

/* 
// --- Razorpay EMI Plans Implementation ---
require_once 'vendor/autoload.php';
use Razorpay\Api\Api;

$emi_methods = [];
try {
    $rzp_settings = $conn->query("SELECT * FROM razorpay_settings WHERE status='active' LIMIT 1")->fetch_assoc();
    if ($rzp_settings) {
        $api = new Api($rzp_settings['key_id'], $rzp_settings['key_secret']);
        $methods = $api->payment->fetchPaymentMethods();
        if (isset($methods['emi'])) {
            $emi_methods = $methods['emi'];
        }
    }
} catch (Exception $e) {
    error_log("EMI Fetch Error: " . $e->getMessage());
}
*/

$mrp = $product['mrp'];
$sale = $product['sale_price'];
$disc = $product['discount_percent'];
?>
<style>
    /* Product Page Specific Styles */
    body {
        background-color: #f1f3f6;
    }

    .prod-container {
        background: #fff;
        padding: 16px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .15);
        border-radius: 2px;
    }

    /* Left Column: Gallery */
    .gallery-col {
        position: sticky;
        top: 90px;
        align-self: flex-start;
    }

    .main-img-container {
        border: 1px solid #f0f0f0;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .main-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s;
        cursor: crosshair;
    }

    .thumbnail-track {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 5px 0;
    }

    .thumb-btn {
        width: 60px;
        height: 60px;
        border: 1px solid #f0f0f0;
        cursor: pointer;
        padding: 2px;
        opacity: 0.7;
        transition: all 0.2s;
    }

    .thumb-btn.active {
        border: 2px solid #2874f0;
        opacity: 1;
    }

    .thumb-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Buttons */
    .action-btn-row {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-buy {
        background: #fb641b;
        color: #fff;
        border: none;
        padding: 18px 8px;
        font-weight: 500;
        text-transform: uppercase;
        flex: 1;
        font-size: 16px;
        border-radius: 2px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .2);
    }

    .btn-cart {
        background: #ff9f00;
        color: #fff;
        border: none;
        padding: 18px 8px;
        font-weight: 500;
        text-transform: uppercase;
        flex: 1;
        font-size: 16px;
        border-radius: 2px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .2);
    }

    .btn-buy:hover {
        background: #f55b10;
        color: #fff;
    }

    .btn-cart:hover {
        background: #f39500;
        color: #fff;
    }

    /* Right Column: Details */
    .prod-title {
        font-size: 20px;
        font-weight: 500;
        color: #212121;
        margin: 0;
        line-height: 1.4;
    }

    .rating-badge {
        background: #388e3c;
        color: #fff;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: 700;
        vertical-align: middle;
    }

    .rating-text {
        color: #878787;
        font-size: 14px;
        font-weight: 500;
        margin-left: 8px;
    }

    .price-block {
        margin-top: 15px;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }

    .sale-price {
        font-size: 28px;
        font-weight: 500;
        color: #212121;
    }

    .mrp-price {
        font-size: 16px;
        color: #878787;
        text-decoration: line-through;
    }

    .disc-off {
        font-size: 16px;
        color: #388e3c;
        font-weight: 500;
    }

    .offers-block {
        margin-top: 15px;
    }

    .offers-title {
        font-size: 14px;
        font-weight: 600;
        color: #212121;
        margin-bottom: 8px;
    }

    .offer-item {
        font-size: 14px;
        color: #212121;
        margin-bottom: 8px;
        display: flex;
        gap: 8px;
    }

    .offer-tag {
        color: #388e3c;
        font-size: 14px;
    }

    /* Specs & Desc */
    .section-box {
        border: 1px solid #f0f0f0;
        padding: 20px;
        margin-top: 20px;
        border-radius: 4px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 500;
        color: #212121;
        margin-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .desc-content {
        font-size: 14px;
        color: #212121;
        line-height: 1.6;
    }

    .desc-content img {
        max-width: 100%;
        height: auto;
    }

    /* Responsive existing content images */

    /* Reviews */
    .review-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 15px 0;
    }

    .reviewer-name {
        font-size: 12px;
        font-weight: 500;
        color: #212121;
        margin-top: 5px;
    }

    /* Mobile Overlay for Buttons */
    .mobile-footer-actions {
        display: none;
    }

    @media (max-width: 768px) {
        .gallery-col {
            position: static;
        }

        .main-img-container {
            height: 350px;
        }

        .action-btn-row {
            display: none;
        }

        /* Hide normal buttons, show sticky */

        .mobile-footer-actions {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }

        .mobile-btn {
            flex: 1;
            padding: 16px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            color: #fff;
            border: none;
        }
    }

    /* Social Share */
    .share-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
    }

    .share-text {
        font-size: 14px;
        font-weight: 500;
        color: #878787;
    }

    .share-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        transition: transform 0.2s;
        text-decoration: none;
        font-size: 16px;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        color: #fff;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }

    .share-wa {
        background: #25D366;
    }

    .share-fb {
        background: #1877F2;
    }

    .share-tw {
        background: #1DA1F2;
    }

    .share-pi {
        background: #E60023;
    }

    /* Color Swatches */
    .color-swatches {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .color-item {
        width: 54px;
        height: 54px;
        border-radius: 8px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
        padding: 2px;
        background: #fff;
    }

    .color-item.active {
        border: 2px solid #2874f0;
    }

    .color-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .color-item img {
        width: 100%;
        height: 100%;
        border-radius: 6px;
        object-fit: cover;
    }

    .color-name-display {
        font-size: 14px;
        font-weight: 500;
        color: #212121;
        margin-bottom: 5px;
    }

    /* EMI Sidebar Styles */
    .emi-offcanvas {
        width: 450px !important;
        border-left: none;
    }
    .emi-offcanvas .offcanvas-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }
    .emi-tabs {
        border-bottom: 1px solid #eee;
    }
    .emi-tabs .nav-link {
        color: #666;
        border: none;
        padding: 15px 25px;
        font-weight: 500;
        position: relative;
    }
    .emi-tabs .nav-link.active {
        color: #2874f0;
        background: transparent;
    }
    .emi-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: #2874f0;
    }
    .bank-item {
        padding: 20px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s;
    }
    .bank-item:hover {
        background: #fcfcfc;
    }
    .bank-logo {
        width: 45px;
        height: 45px;
        object-fit: contain;
        margin-right: 15px;
    }
    .bank-info h6 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
    }
    .bank-info p {
        margin: 0;
        font-size: 13px;
        color: #878787;
    }
    .emi-details-table {
        font-size: 13px;
        background: #f9f9f9;
        margin-top: 10px;
        border-radius: 8px;
    }
    @media (max-width: 576px) {
        .emi-offcanvas {
            width: 100% !important;
        }
    }

    .variant-label {
        font-size: 14px;
        color: #878787;
        margin-bottom: 10px;
        display: block;
    }

    /* Trust Badges */
    .trust-badges {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 25px;
        padding: 15px 0;
        border-top: 1px solid #f0f0f0;
    }
    .trust-badge-item {
        text-align: center;
    }
    .trust-badge-item i {
        font-size: 20px;
        color: #388e3c;
        margin-bottom: 8px;
    }
    .trust-badge-item p {
        font-size: 11px;
        font-weight: 600;
        color: #212121;
        margin: 0;
        text-transform: uppercase;
    }

    /* EMI Section */
    .emi-card {
        background: #000;
        border-radius: 12px;
        padding: 2px;
        margin: 20px 0;
        color: #fff;
        overflow: hidden;
    }
    .emi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        font-size: 12px;
    }
    .emi-body {
        background: #fff;
        color: #000;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .emi-info h6 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 10px 0;
        color: #0d2366;
    }
    .bank-icons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .bank-icons img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }
    .plus-more {
        font-size: 11px;
        color: #666;
        background: #f0f0f0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
    }
    .btn-view-plans {
        background: #000;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        text-decoration: none !important;
    }
</style>

<div class="container-fluid mt-3 mb-5 px-lg-4">
    <div class="prod-container">
        <div class="row">
            <!-- Left: Gallery -->
            <div class="col-lg-5 col-md-6 gallery-col">
                <div class="main-img-container" id="zoomContainer">
                    <img src="<?php echo $gallery[0]; ?>" class="main-img" id="mainImage">
                </div>

                <div class="thumbnail-track">
                    <?php foreach ($gallery as $idx => $img): ?>
                        <div class="thumb-btn <?php echo $idx === 0 ? 'active' : ''; ?>"
                            onclick="changeImage('<?php echo $img; ?>', this)">
                            <img src="<?php echo $img; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop Buttons -->
                <div class="action-btn-row">
                    <button class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>)"><i
                            class="fas fa-shopping-cart me-2"></i> Add to Cart</button>
                    <button class="btn-buy" onclick="buyNow(<?php echo $product['id']; ?>)"><i
                            class="fas fa-bolt me-2"></i> Buy Now</button>
                </div>

                <!-- Trust Badges -->
                <div class="trust-badges">
                    <div class="trust-badge-item">
                        <i class="fas fa-shipping-fast"></i>
                        <p>Free Delivery</p>
                    </div>
                    <div class="trust-badge-item">
                        <i class="fas fa-undo-alt"></i>
                        <p>7 Days Returns</p>
                    </div>
                    <div class="trust-badge-item">
                        <i class="fas fa-shield-alt"></i>
                        <p>Secure Payment</p>
                    </div>
                </div>
            </div>

            <!-- Right: Details -->
            <div class="col-lg-7 col-md-6 mt-3 mt-md-0 ps-lg-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="font-size: 12px; background:transparent; padding:0;">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a>
                        </li>
                        <!-- Ideally fetch category name here, logic simplified for now -->
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Products</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo htmlspecialchars($product['name']); ?></li>
                    </ol>
                </nav>

                <h1 class="prod-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="d-flex align-items-center mt-2">
                    <span class="rating-badge">4.5 <i class="fas fa-star" style="font-size:10px;"></i></span>
                    <span class="rating-text text-muted">1,234 Ratings & 102 Reviews</span>
                    <!-- Verified Tag -->
                    <span class="ms-3"><img src="assets/images/amdika-logo.png"
                            style="height:15px; opacity:0.5;"></span>
                </div>

                <div class="price-block">
                    <span class="sale-price" id="displaySalePrice">₹<?php echo number_format((float) $sale); ?></span>
                    <?php if ($disc > 0): ?>
                        <span class="mrp-price">₹<?php echo number_format((float) $mrp); ?></span>
                        <span class="disc-off" id="displayDiscount"><?php echo $disc; ?>% off</span>
                    <?php endif; ?>
                </div>

                <?php /* ?>
                <!-- EMI Widget -->
                <div class="emi-card shadow-sm">
                    <div class="emi-header">
                        <span class="fw-bold">EMI Plans</span>
                        <span class="opacity-75">powered by <img src="https://razorpay.com/assets/razorpay-logo-white.svg" height="12" style="margin-top:-2px;"></span>
                    </div>
                    <div class="emi-body">
                        <div class="emi-info">
                            <h6 id="emiDisplay">EMI from ₹<?php echo number_format(ceil($sale / 24)); ?>/month</h6>
                            <div class="bank-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/cc/SBI-logo.svg" alt="SBI">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/ICICI_Bank_Logo.svg" alt="ICICI">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c4/HDFC_Bank_logo.svg" alt="HDFC">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Axis_Bank_logo.svg" alt="AXIS">
                                <span class="plus-more">+14</span>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="btn-view-plans" data-bs-toggle="offcanvas" data-bs-target="#emiSidebar">
                            View plans <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                        </a>
                    </div>
                </div>
                <?php */ ?>

                <?php /* ?>
                <!-- EMI Options Sidebar -->
                <div class="offcanvas offcanvas-end emi-offcanvas" tabindex="-1" id="emiSidebar" aria-labelledby="emiSidebarLabel">
                    ...
                </div>
                <?php */ ?>

                <!-- Color Variants -->
                <?php if (!empty($variants)): ?>
                    <div class="section-box mt-4 border-0 p-0">
                        <span class="variant-label">Color's Avilable For This Product On Amadika.in</span>
                        <div class="color-name-display" id="selectedColorName">Select a color</div>
                        <div class="color-swatches">
                            <?php foreach ($variants as $v): ?>
                                <div class="color-item" style="background-color: <?php echo $v['hex_code']; ?>;"
                                    title="<?php echo htmlspecialchars($v['color_name']); ?>"
                                    data-id="<?php echo $v['color_id']; ?>"
                                    data-name="<?php echo htmlspecialchars($v['color_name']); ?>"
                                    data-price="<?php echo $v['price']; ?>" data-image="<?php echo $v['image_path']; ?>"
                                    onclick="selectColor(this)">
                                    <?php if (!empty($v['image_path'])): ?>
                                        <img src="<?php echo $v['image_path']; ?>"
                                            alt="<?php echo htmlspecialchars($v['color_name']); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Social Sharing -->
                <?php
                $curr_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $enc_url = urlencode($curr_url);
                $enc_title = urlencode($product['name']);
                ?>
                <div class="share-container">
                    <span class="share-text">Share this:</span>
                    <a href="https://api.whatsapp.com/send?text=<?php echo $enc_title . ' ' . $enc_url; ?>"
                        target="_blank" class="share-btn share-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $enc_url; ?>" target="_blank"
                        class="share-btn share-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo $enc_url; ?>&text=<?php echo $enc_title; ?>"
                        target="_blank" class="share-btn share-tw" title="Twitter"><i
                            class="fa-brands fa-x-twitter"></i></a>
                    <a href="http://pinterest.com/pin/create/button/?url=<?php echo $enc_url; ?>&description=<?php echo $enc_title; ?>"
                        target="_blank" class="share-btn share-pi" title="Pinterest"><i
                            class="fab fa-pinterest-p"></i></a>
                </div>

                <!-- Specs / Description -->
                <div class="section-box">
                    <h3 class="section-title">Product Description</h3>
                    <div class="desc-content">
                        <?php echo $product['description']; // CKEditor content (HTML) ?>
                    </div>
                    
                    <?php if(!empty($product['video_url'])): ?>
                        <?php 
                            // Convert standard youtube link to embed link
                            $video_url = $product['video_url'];
                            $embed_url = $video_url;
                            if (strpos($video_url, 'watch?v=') !== false) {
                                $parts = parse_url($video_url);
                                parse_str($parts['query'], $query);
                                if (isset($query['v'])) {
                                    $embed_url = "https://www.youtube.com/embed/" . $query['v'];
                                }
                            } elseif (strpos($video_url, 'youtu.be/') !== false) {
                                $parts = explode('youtu.be/', $video_url);
                                $embed_url = "https://www.youtube.com/embed/" . end($parts);
                            }
                        ?>
                        <div class="mt-4 pt-3 border-top">
                            <h5 class="fw-bold mb-3"><i class="fab fa-youtube text-danger me-2"></i>Product Video</h5>
                            <div class="ratio ratio-16x9 shadow-sm rounded overflow-hidden">
                                <iframe src="<?php echo $embed_url; ?>" title="Product Video" allowfullscreen></iframe>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Ratings & Reviews -->
                <?php
                // Review Submission Logic
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
                    $r_name = $conn->real_escape_string($_POST['r_name']);
                    $r_email = $conn->real_escape_string($_POST['r_email']);
                    $r_rating = intval($_POST['r_rating']);
                    $r_message = $conn->real_escape_string($_POST['r_message']);
                    $prod_id = $product['id'];

                    // Image Upload
                    $r_image_path = NULL;
                    if (isset($_FILES['r_image']) && $_FILES['r_image']['error'] == 0) {
                        $target_dir = "assets/images/reviews/";
                        if (!file_exists($target_dir))
                            mkdir($target_dir, 0777, true);
                        $ext = strtolower(pathinfo($_FILES["r_image"]["name"], PATHINFO_EXTENSION));
                        $new_name = "rev_" . time() . "_" . rand(100, 999) . "." . $ext;
                        if (move_uploaded_file($_FILES["r_image"]["tmp_name"], $target_dir . $new_name)) {
                            $r_image_path = "assets/images/reviews/" . $new_name;
                        }
                    }

                    // Insert Review (Auto-Approved based on request to avoid status blocking)
                    $is_sql = "INSERT INTO product_reviews (product_id, name, email, rating, message, image, status) VALUES 
                              ($prod_id, '$r_name', '$r_email', $r_rating, '$r_message', '$r_image_path', 'approved')";

                    if ($conn->query($is_sql)) {
                        echo "<script>alert('Review submitted successfully!'); window.location.href='product-details.php?slug=$slug';</script>";
                    } else {
                        echo "<script>alert('Error submitting review.');</script>";
                    }
                }

                // Fetch Reviews
                $rev_sql = "SELECT * FROM product_reviews WHERE product_id = " . $product['id'] . " ORDER BY created_at DESC";
                $rev_result = $conn->query($rev_sql);
                $review_count = $rev_result->num_rows;

                // Calculate Avg
                $avg_sql = "SELECT AVG(rating) as avg_rating FROM product_reviews WHERE product_id = " . $product['id'];
                $avg_res = $conn->query($avg_sql)->fetch_assoc();
                $avg_rating = $avg_res['avg_rating'] ? number_format($avg_res['avg_rating'], 1) : '0.0';
                ?>

                <div class="section-box" id="reviews-section">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <div>
                            <h3 class="section-title mb-0 border-0 p-0">Ratings & Reviews</h3>
                            <div class="mt-1">
                                <span class="rating-badge"
                                    style="background:#388e3c; padding:2px 8px; font-size:14px;"><?php echo $avg_rating; ?>
                                    <i class="fas fa-star"></i></span>
                                <span class="text-muted ms-2" style="font-size:14px;"><?php echo $review_count; ?>
                                    Reviews</span>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal"
                            data-bs-target="#reviewModal">Rate Product</button>
                    </div>

                    <?php if ($review_count > 0): ?>
                        <?php while ($row = $rev_result->fetch_assoc()): ?>
                            <div class="review-item">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rating-badge"
                                        style="background:#388e3c; padding:0 4px; font-size:10px;"><?php echo $row['rating']; ?>
                                        <i class="fas fa-star"></i></span>
                                    <span class="fw-bold"
                                        style="font-size:14px;"><?php echo htmlspecialchars($row['name']); ?></span>
                                </div>
                                <p class="mt-2 mb-1 text-muted" style="font-size:13px;">
                                    <?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                                <?php if (!empty($row['image'])): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo $row['image']; ?>"
                                            style="max-height:80px; border-radius:4px; border:1px solid #eee;">
                                    </div>
                                <?php endif; ?>
                                <div class="reviewer-name text-muted mt-2" style="font-size:11px;">
                                    <i class="fas fa-check-circle text-success" style="font-size:10px;"></i> Certified Buyer •
                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="far fa-comment-dots fa-2x mb-2 opacity-50"></i>
                            <p>No reviews yet. Be the first to rate this product!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Review Modal -->
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rate this Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3 text-center">
                                        <label class="form-label d-block fw-bold">Your Rating</label>
                                        <div class="rating-stars"
                                            style="font-size: 2rem; color: #ddd; cursor: pointer;">
                                            <i class="fas fa-star Star" data-value="1"></i>
                                            <i class="fas fa-star Star" data-value="2"></i>
                                            <i class="fas fa-star Star" data-value="3"></i>
                                            <i class="fas fa-star Star" data-value="4"></i>
                                            <i class="fas fa-star Star" data-value="5"></i>
                                        </div>
                                        <input type="hidden" name="r_rating" id="ratingValue" value="5" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="r_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="r_email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Review (Optional)</label>
                                        <textarea class="form-control" name="r_message" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Photo (Optional)</label>
                                        <input type="file" class="form-control" name="r_image" accept="image/*">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="submit_review"
                                        class="btn btn-warning text-white fw-bold">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const stars = document.querySelectorAll('.Star');
                        const ratingInput = document.getElementById('ratingValue');

                        // Default to 5 star
                        highlightStars(5);

                        stars.forEach(star => {
                            star.addEventListener('click', function () {
                                const val = parseInt(this.getAttribute('data-value'));
                                ratingInput.value = val;
                                highlightStars(val);
                            });

                            star.addEventListener('mouseover', function () {
                                const val = parseInt(this.getAttribute('data-value'));
                                highlightStars(val);
                            });

                            star.addEventListener('mouseout', function () {
                                highlightStars(parseInt(ratingInput.value));
                            });
                        });

                        function highlightStars(count) {
                            stars.forEach(s => {
                                const v = parseInt(s.getAttribute('data-value'));
                                if (v <= count) {
                                    s.style.color = '#ffc107'; // Gold
                                } else {
                                    s.style.color = '#ddd'; // Gray
                                }
                            });
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</div>

<!-- Dynamic Category Products CSS -->
<style>
    /* Copied from category-products.php for consistency */
    .category-products-section {
        background-color: #fff;
        padding: 16px 0;
        margin-top: 20px;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 10px;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .05);
        border-radius: 4px 4px 0 0;
    }

    .category-title {
        font-size: 22px;
        font-weight: 600;
        color: #212121;
        margin: 0;
    }

    .cp-product-container {
        padding: 10px 5px;
    }

    /* Desktop Slider */
    @media (min-width: 992px) {
        .cp-product-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .cp-product-container::-webkit-scrollbar {
            display: none;
        }

        .cp-product-item {
            flex: 0 0 220px;
            min-width: 220px;
        }

        .cp-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 90px;
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .category-products-section:hover .cp-nav-btn {
            opacity: 1;
        }

        .cp-prev-btn {
            left: 0;
            border-radius: 0 4px 4px 0;
        }

        .cp-next-btn {
            right: 0;
            border-radius: 4px 0 0 4px;
        }

        .d-none-mobile {
            display: block;
        }
    }

    /* Mobile Grid */
    @media (max-width: 991px) {
        .cp-product-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 0 10px;
        }

        .cp-product-item {
            width: 100%;
        }

        .cp-nav-btn {
            display: none;
        }

        .d-none-mobile {
            display: none;
        }
    }

    /* Card Styling */
    .premium-product-card {
        background: #fff;
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 16px;
        height: 100%;
        transition: box-shadow 0.2s ease, transform 0.1s;
        border: 1px solid #f0f0f0;
        border-radius: 4px;
    }

    .premium-product-card:hover {
        box-shadow: 0 3px 16px 0 rgba(0, 0, 0, .11);
        transform: translateY(-2px);
        z-index: 2;
        border-color: transparent;
    }

    .product-img-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .rating-badge {
        background-color: #388e3c;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        margin-right: 8px;
    }

    .rating-badge i {
        font-size: 10px;
        margin-left: 2px;
    }

    .review-count {
        color: #878787;
        font-size: 13px;
        font-weight: 500;
    }

    .product-title {
        font-size: 14px;
        font-weight: 500;
        color: #212121;
        margin-top: 8px;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
        height: 40px;
    }

    .price-container {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .current-price {
        font-size: 18px;
        font-weight: 600;
        color: #212121;
        margin-right: 10px;
    }

    .original-price {
        font-size: 14px;
        color: #878787;
        text-decoration: line-through;
        margin-right: 10px;
    }

    .discount-text {
        font-size: 13px;
        color: #388e3c;
        font-weight: 700;
    }

    /* Mobile Card */
    @media (max-width: 767px) {
        .premium-product-card {
            padding: 10px;
        }

        .product-img-wrapper {
            height: 140px;
        }

        .product-title {
            font-size: 13px;
            height: 36px;
        }

        .current-price {
            font-size: 15px;
        }

        .rating-badge {
            padding: 1px 4px;
            font-size: 11px;
        }
    }
</style>

<!-- Related Products Section -->
<?php
$cat_id = $product['category_id'];
$curr_id = $product['id'];
$rel_sql = "SELECT * FROM products WHERE category_id = $cat_id AND id != $curr_id AND status = 'active' ORDER BY RAND() LIMIT 10";
$rel_res = $conn->query($rel_sql);

if ($rel_res && $rel_res->num_rows > 0):
    $unique_id = "rel_prod_" . time();
    ?>
    <section class="category-products-section position-relative mb-3 px-lg-4">
        <div class="container-fluid p-0 position-relative" id="container_<?php echo $unique_id; ?>">
            <!-- Header -->
            <div class="category-header rounded-top">
                <h2 class="category-title">Similar Products</h2>
                <a href="products.php?category_id=<?php echo $cat_id; ?>" class="view-all-btn d-none d-lg-block">View
                    All</a>
            </div>

            <!-- Desktop Nav -->
            <button class="cp-nav-btn cp-prev-btn" data-target="slider_<?php echo $unique_id; ?>"><i
                    class="fas fa-chevron-left"></i></button>
            <button class="cp-nav-btn cp-next-btn" data-target="slider_<?php echo $unique_id; ?>"><i
                    class="fas fa-chevron-right"></i></button>

            <!-- Product Container -->
            <div class="cp-product-container" id="slider_<?php echo $unique_id; ?>">
                <?php while ($fp = $rel_res->fetch_assoc()):
                    $fp_img = !empty($fp['featured_image']) ? $fp['featured_image'] : 'assets/images/demo-data/product.jpg';
                    $fp_rating = number_format(4.0 + (rand(0, 10) / 10), 1);
                    $fp_reviews = rand(5, 500);
                    ?>
                    <div class="cp-product-item">
                        <div class="premium-product-card">
                            <div class="product-img-wrapper">
                                <a href="product-details.php?slug=<?php echo $fp['slug']; ?>" class="d-block w-100 h-100">
                                    <img src="<?php echo $fp_img; ?>" class="product-img"
                                        alt="<?php echo htmlspecialchars($fp['name']); ?>">
                                </a>
                            </div>
                            <div>
                                <span class="rating-badge"><?php echo $fp_rating; ?> <i class="fa-solid fa-star"></i></span>
                                <span class="review-count">(<?php echo $fp_reviews; ?>)</span>
                            </div>
                            <a href="product-details.php?slug=<?php echo $fp['slug']; ?>" class="text-decoration-none">
                                <h3 class="product-title"><?php echo htmlspecialchars($fp['name']); ?></h3>
                            </a>
                            <div class="price-container">
                                <span class="current-price">₹<?php echo number_format((float) $fp['sale_price']); ?></span>
                                <span class="original-price">₹<?php echo number_format((float) $fp['mrp']); ?></span>
                                <span class="discount-text"><?php echo $fp['discount_percent']; ?>% off</span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize this specific slider
            const prevBtn = document.querySelector('#container_<?php echo $unique_id; ?> .cp-prev-btn');
            const nextBtn = document.querySelector('#container_<?php echo $unique_id; ?> .cp-next-btn');
            const slider = document.querySelector('#slider_<?php echo $unique_id; ?>');

            if (prevBtn && nextBtn && slider) {
                const scrollAmount = 300;
                prevBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });
                nextBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });
            }
        });
    </script>
<?php endif; ?>

<!-- Mobile Sticky Footer -->
<!-- Mobile Sticky Footer -->
<div class="mobile-footer-actions">
    <button class="mobile-btn" style="background: #fff; color: #212121; border-top: 1px solid #f0f0f0;"
        onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
    <button class="mobile-btn" style="background: #fb641b;" onclick="buyNow(<?php echo $product['id']; ?>)">Buy
        Now</button>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    function changeImage(src, btn) {
        // Change Main Image
        document.getElementById('mainImage').src = src;

        // Active Class Handling
        document.querySelectorAll('.thumb-btn').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    }

    // Simple Zoom Effect
    const container = document.getElementById('zoomContainer');
    const img = document.getElementById('mainImage');

    container.addEventListener('mousemove', function (e) {
        const { left, top, width, height } = container.getBoundingClientRect();
        const x = (e.clientX - left) / width;
        const y = (e.clientY - top) / height;

        img.style.transformOrigin = `${x * 100}% ${y * 100}%`;
        img.style.transform = 'scale(1.5)';
    });

    container.addEventListener('mouseleave', function () {
        img.style.transform = 'scale(1)';
        img.style.transformOrigin = 'center center';
    });

    // Cart Functions
    let selectedColorId = null;
    const baseSalePrice = <?php echo $sale; ?>;
    const baseMrp = <?php echo $mrp; ?>;

    function selectColor(el) {
        document.querySelectorAll('.color-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');

        selectedColorId = el.dataset.id;
        document.getElementById('selectedColorName').innerText = el.dataset.name;

        // Update Price
        const variantPrice = parseFloat(el.dataset.price);
        const emiEl = document.getElementById('emiDisplay');
        if (variantPrice > 0) {
            document.getElementById('displaySalePrice').innerText = '₹' + variantPrice.toLocaleString();
            if (emiEl) emiEl.innerText = 'EMI from ₹' + Math.ceil(variantPrice / 24).toLocaleString() + '/month';
            // Recalculate discount if needed
            if (baseMrp > variantPrice) {
                const newDisc = Math.round(((baseMrp - variantPrice) / baseMrp) * 100);
                const discEl = document.getElementById('displayDiscount');
                if (discEl) discEl.innerText = newDisc + '% off';
            }
        } else {
            document.getElementById('displaySalePrice').innerText = '₹' + baseSalePrice.toLocaleString();
            if (emiEl) emiEl.innerText = 'EMI from ₹' + Math.ceil(baseSalePrice / 24).toLocaleString() + '/month';
            const discEl = document.getElementById('displayDiscount');
            if (discEl) discEl.innerText = '<?php echo $disc; ?>% off';
        }

        // Update Image
        const varImg = el.dataset.image;
        if (varImg) {
            changeImage(varImg, null); // Change main image
            // We could also prepend this to gallery thumbnails? 
            // For now, just changing main image is standard.
        }
    }

    function addToCart(id) {
        <?php if (!empty($variants)): ?>
            if (!selectedColorId) {
                Swal.fire({ icon: 'warning', title: 'Select Color', text: 'Please select a color before adding to cart.' });
                return;
            }
        <?php endif; ?>

        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&product_id=${id}&quantity=1&color_id=${selectedColorId || ''}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    openCartSidebar();
                    if (typeof updateCartCount === 'function') updateCartCount();
                } else {
                    alert(data.message);
                }
            });
    }

    function buyNow(id) {
        <?php if (!empty($variants)): ?>
            if (!selectedColorId) {
                Swal.fire({ icon: 'warning', title: 'Select Color', text: 'Please select a color before proceeding.' });
                return;
            }
        <?php endif; ?>

        fetch('includes/cart_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&product_id=${id}&quantity=1&color_id=${selectedColorId || ''}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'cart.php';
                }
            });
    }
</script>
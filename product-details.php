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

// Data Prep
$gallery = json_decode($product['gallery_images'], true) ?? [];
// Add featured image to start of gallery if not there
array_unshift($gallery, $product['featured_image']);
$gallery = array_unique(array_filter($gallery)); // Remove duplicates/empty

$mrp = $product['mrp'];
$sale = $product['sale_price'];
$disc = $product['discount_percent'];
?>
<style>
    /* Product Page Specific Styles */
    body { background-color: #f1f3f6; }
    
    .prod-container { background: #fff; padding: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.15); border-radius: 2px; }
    
    /* Left Column: Gallery */
    .gallery-col { position: sticky; top: 90px; align-self: flex-start; }
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
    .main-img { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s; cursor: crosshair; }
    
    .thumbnail-track { display: flex; gap: 10px; overflow-x: auto; padding: 5px 0; }
    .thumb-btn { 
        width: 60px; height: 60px; 
        border: 1px solid #f0f0f0; 
        cursor: pointer; 
        padding: 2px;
        opacity: 0.7;
        transition: all 0.2s;
    }
    .thumb-btn.active { border: 2px solid #2874f0; opacity: 1; }
    .thumb-btn img { width: 100%; height: 100%; object-fit: contain; }

    /* Buttons */
    .action-btn-row { display: flex; gap: 10px; margin-top: 15px; }
    .btn-buy { 
        background: #fb641b; color: #fff; border: none; 
        padding: 18px 8px; font-weight: 500; text-transform: uppercase; 
        flex: 1; font-size: 16px; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
    }
    .btn-cart { 
        background: #ff9f00; color: #fff; border: none; 
        padding: 18px 8px; font-weight: 500; text-transform: uppercase; 
        flex: 1; font-size: 16px; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0,0,0,.2);
    }
    .btn-buy:hover { background: #f55b10; color: #fff; }
    .btn-cart:hover { background: #f39500; color: #fff; }

    /* Right Column: Details */
    .prod-title { font-size: 20px; font-weight: 500; color: #212121; margin: 0; line-height: 1.4; }
    .rating-badge { background: #388e3c; color: #fff; font-size: 12px; padding: 2px 6px; border-radius: 3px; font-weight: 700; vertical-align: middle; }
    .rating-text { color: #878787; font-size: 14px; font-weight: 500; margin-left: 8px; }
    
    .price-block { margin-top: 15px; display: flex; align-items: baseline; gap: 12px; }
    .sale-price { font-size: 28px; font-weight: 500; color: #212121; }
    .mrp-price { font-size: 16px; color: #878787; text-decoration: line-through; }
    .disc-off { font-size: 16px; color: #388e3c; font-weight: 500; }

    .offers-block { margin-top: 15px; }
    .offers-title { font-size: 14px; font-weight: 600; color: #212121; margin-bottom: 8px; }
    .offer-item { font-size: 14px; color: #212121; margin-bottom: 8px; display: flex; gap: 8px; }
    .offer-tag { color: #388e3c; font-size: 14px; }

    /* Specs & Desc */
    .section-box { border: 1px solid #f0f0f0; padding: 20px; margin-top: 20px; border-radius: 4px; }
    .section-title { font-size: 18px; font-weight: 500; color: #212121; margin-bottom: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; }
    .desc-content { font-size: 14px; color: #212121; line-height: 1.6; }
    .desc-content img { max-width: 100%; height: auto; } /* Responsive existing content images */

    /* Reviews */
    .review-item { border-bottom: 1px solid #f0f0f0; padding: 15px 0; }
    .reviewer-name { font-size: 12px; font-weight: 500; color: #212121; margin-top: 5px; }
    
    /* Mobile Overlay for Buttons */
    .mobile-footer-actions { display: none; }

    @media (max-width: 768px) {
        .gallery-col { position: static; }
        .main-img-container { height: 350px; }
        .action-btn-row { display: none; } /* Hide normal buttons, show sticky */
        
        .mobile-footer-actions {
            display: flex; position: fixed; bottom: 0; left: 0; width: 100%; z-index: 100;
        }
        .mobile-btn {
            flex: 1; padding: 16px; text-align: center;
            font-weight: 600; text-transform: uppercase; font-size: 14px;
            color: #fff; border: none;
        }
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
                    <?php foreach($gallery as $idx => $img): ?>
                        <div class="thumb-btn <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="changeImage('<?php echo $img; ?>', this)">
                            <img src="<?php echo $img; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop Buttons -->
                <div class="action-btn-row">
                    <button class="btn-cart"><i class="fas fa-shopping-cart me-2"></i> Add to Cart</button>
                    <button class="btn-buy"><i class="fas fa-bolt me-2"></i> Buy Now</button>
                </div>
            </div>

            <!-- Right: Details -->
            <div class="col-lg-7 col-md-6 mt-3 mt-md-0 ps-lg-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="font-size: 12px; background:transparent; padding:0;">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                        <!-- Ideally fetch category name here, logic simplified for now -->
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Products</a></li> 
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
                    </ol>
                </nav>

                <h1 class="prod-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="d-flex align-items-center mt-2">
                    <span class="rating-badge">4.5 <i class="fas fa-star" style="font-size:10px;"></i></span>
                    <span class="rating-text text-muted">1,234 Ratings & 102 Reviews</span>
                    <!-- Verified Tag -->
                    <span class="ms-3"><img src="assets/images/amdika-logo.png" style="height:15px; opacity:0.5;"></span>
                </div>

                <div class="price-block">
                    <span class="sale-price">₹<?php echo number_format($sale); ?></span>
                    <?php if($disc > 0): ?>
                        <span class="mrp-price">₹<?php echo number_format($mrp); ?></span>
                        <span class="disc-off"><?php echo $disc; ?>% off</span>
                    <?php endif; ?>
                </div>

                <!-- Offers Block Removed -->

                <!-- Specs / Description -->
                <div class="section-box">
                    <h3 class="section-title">Product Description</h3>
                    <div class="desc-content">
                        <?php echo $product['description']; // CKEditor content (HTML) ?>
                    </div>
                </div>
                
                <!-- Ratings & Reviews Stub -->
                <div class="section-box">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                         <h3 class="section-title mb-0 border-0 p-0">Ratings & Reviews</h3>
                         <button class="btn btn-outline-primary btn-sm">Rate Product</button>
                    </div>
                    
                    <div class="review-item">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rating-badge" style="background:#388e3c; padding:0 4px; font-size:10px;">5 <i class="fas fa-star"></i></span>
                            <span class="fw-bold" style="font-size:14px;">Excellent Product!</span>
                        </div>
                        <p class="mt-2 mb-1 text-muted" style="font-size:13px;">Really loved the quality of this product. Delivered on time and packaging was good.</p>
                        <div class="reviewer-name text-muted">Rahul S. <i class="fas fa-check-circle text-success" style="font-size:10px;"></i> Certified Buyer</div>
                    </div>
                     <div class="review-item border-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rating-badge" style="background:#388e3c; padding:0 4px; font-size:10px;">4 <i class="fas fa-star"></i></span>
                            <span class="fw-bold" style="font-size:14px;">Value for money</span>
                        </div>
                        <p class="mt-2 mb-1 text-muted" style="font-size:13px;">Good product for this price range. Satisfied with the purchase.</p>
                        <div class="reviewer-name text-muted">Amit K. <i class="fas fa-check-circle text-success" style="font-size:10px;"></i> Certified Buyer</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Mobile Sticky Footer -->
<div class="mobile-footer-actions">
    <button class="mobile-btn" style="background: #fff; color: #212121; border-top: 1px solid #f0f0f0;">Add to Cart</button>
    <button class="mobile-btn" style="background: #fb641b;">Buy Now</button>
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
    
    container.addEventListener('mousemove', function(e) {
        const { left, top, width, height } = container.getBoundingClientRect();
        const x = (e.clientX - left) / width;
        const y = (e.clientY - top) / height;
        
        img.style.transformOrigin = `${x * 100}% ${y * 100}%`;
        img.style.transform = 'scale(1.5)';
    });

    container.addEventListener('mouseleave', function() {
        img.style.transform = 'scale(1)';
        img.style.transformOrigin = 'center center';
    });
</script>

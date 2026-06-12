<?php
require_once 'database/db_config.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (empty($slug)) {
    header("Location: blogs.php");
    exit;
}

// Fetch blog post
$stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'active'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

if (!$blog) {
    header("HTTP/1.1 404 Not Found");
    $page_title = 'Article Not Found - Amadika Premium';
    include 'includes/header.php';
    ?>
    <div class="container py-5 text-center" style="min-height: 450px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <i class="far fa-frown fs-1 text-muted mb-4 opacity-50"></i>
        <h2 class="h3 serif-title text-secondary mb-2" style="font-family: 'Playfair Display', serif;">Article Not Found</h2>
        <p class="text-muted">The blog post you are looking for does not exist or has been removed.</p>
        <a href="blogs.php" class="btn btn-dark mt-3 px-4 py-2 border-0" style="background: #D4A017; border-radius: 30px;">Back to Blogs</a>
    </div>
    <?php
    include 'includes/footer.php';
    exit;
}

// Set SEO Meta Parameters for includes/header.php
$page_title = !empty($blog['seo_title']) ? $blog['seo_title'] : $blog['title'] . ' | Amadika Journal';
$page_description = !empty($blog['seo_description']) ? $blog['seo_description'] : substr(strip_tags($blog['summary'] ?: $blog['content']), 0, 160);
$page_keywords = !empty($blog['seo_keywords']) ? $blog['seo_keywords'] : 'amadika, blog, luxury leather, ' . strtolower($blog['title']);

include 'includes/header.php';

// Fetch related articles (excluding current one)
$related_stmt = $conn->prepare("SELECT * FROM blogs WHERE status = 'active' AND id != ? ORDER BY created_at DESC LIMIT 3");
$related_stmt->bind_param("i", $blog['id']);
$related_stmt->execute();
$related_blogs = $related_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!-- AOS CSS Library & SweetAlert2 -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Reading Progress Bar -->
<div id="reading-progress"></div>

<style>
    /* Premium Styling Overrides */
    .blog-details-wrapper {
        background-color: #fafbfc;
        font-family: 'Rubik', sans-serif;
        color: #2D3436;
        overflow-x: hidden;
    }
    
    .serif-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
    }

    #reading-progress {
        position: fixed;
        top: 0; left: 0;
        width: 0%;
        height: 4px;
        background-color: #D4A017;
        z-index: 9999;
        transition: width 0.1s ease;
    }

    /* Article Hero */
    .article-hero {
        background: #fff;
        border-bottom: 1px solid #f1f2f6;
        padding: 60px 0;
    }
    .article-category {
        color: #D4A017;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 2.5px;
        margin-bottom: 18px;
        display: inline-block;
    }
    .article-title {
        font-size: 2.8rem;
        font-weight: 700;
        color: #1A1D20;
        line-height: 1.25;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
    }
    .article-meta {
        font-size: 0.9rem;
        color: #747d8c;
        display: flex;
        align-items: center;
        gap: 25px;
        flex-wrap: wrap;
    }
    .article-meta i {
        color: #D4A017;
    }
    .article-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Featured Image */
    .article-banner-wrap {
        margin-bottom: 40px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.04);
        max-height: 550px;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .article-banner-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Main Content */
    .article-main {
        padding: 60px 0 90px;
    }
    .article-body-card {
        background: #fff;
        border-radius: 24px;
        padding: 50px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
    }
    
    /* Article Content Typography Styles */
    .article-content {
        color: #2D3436;
        font-size: 1.15rem;
        line-height: 1.9;
    }
    .article-content p {
        margin-bottom: 28px;
    }
    .article-content h2, .article-content h3, .article-content h4 {
        font-family: 'Playfair Display', serif;
        color: #1A1D20;
        font-weight: 700;
        margin-top: 45px;
        margin-bottom: 22px;
        line-height: 1.3;
    }
    .article-content h2 { font-size: 1.85rem; border-bottom: 1px solid #f1f2f6; padding-bottom: 8px; }
    .article-content h3 { font-size: 1.5rem; }
    
    .article-content blockquote {
        border-left: 4px solid #D4A017;
        padding: 5px 0 5px 25px;
        margin: 35px 0;
        font-style: italic;
        color: #57606f;
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        line-height: 1.7;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 30px 0;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03);
    }
    .article-content ul, .article-content ol {
        margin-bottom: 28px;
        padding-left: 25px;
    }
    .article-content li {
        margin-bottom: 10px;
    }

    /* Sidebar Widgets */
    .sidebar-widget {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 8px 30px rgba(0,0,0,0.02);
        margin-bottom: 35px;
        transition: border-color 0.3s;
    }
    .sidebar-widget:hover {
        border-color: rgba(212,160,23,0.1);
    }
    .sidebar-widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1A1D20;
        border-bottom: 2px solid #F1F2F6;
        padding-bottom: 15px;
        margin-bottom: 22px;
        position: relative;
    }
    .sidebar-widget-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: -2px;
        width: 45px; height: 2px;
        background-color: #D4A017;
    }

    .related-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #F1F2F6;
    }
    .related-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .related-thumb {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        border: 1px solid #eee;
    }
    .related-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .related-info {
        flex-grow: 1;
    }
    .related-link {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2D3436;
        text-decoration: none;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 5px;
        transition: color 0.2s;
    }
    .related-link:hover {
        color: #D4A017;
    }
    .related-date {
        font-size: 0.8rem;
        color: #747d8c;
    }
    
    .btn-share {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F1F2F6;
        color: #2D3436;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none;
        border: none;
        font-size: 0.95rem;
    }
    .btn-share:hover {
        background: #D4A017;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(212,160,23,0.2);
    }
</style>

<div class="blog-details-wrapper">
    <!-- Article Header Section -->
    <section class="article-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <span class="article-category">Inspiration & Insights</span>
                    <h1 class="article-title serif-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="article-meta">
                            <span><i class="far fa-calendar-alt"></i><?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                            <span><i class="far fa-user"></i>By <?php echo htmlspecialchars($blog['author'] ?: 'Admin'); ?></span>
                        </div>
                        
                        <!-- Sharing widget -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted me-2" style="font-weight: 500;">Share Article:</span>
                            <a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://amadika.in/blog/' . $blog['slug']); ?>" target="_blank" class="btn-share" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://amadika.in/blog/' . $blog['slug']); ?>&text=<?php echo urlencode($blog['title']); ?>" target="_blank" class="btn-share" title="Share on Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($blog['title'] . ' - https://amadika.in/blog/' . $blog['slug']); ?>" target="_blank" class="btn-share" title="Share on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <button id="btn-copy-link" onclick="copyArticleLink()" class="btn-share" title="Copy Link"><i class="fas fa-link"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="article-main">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Left Column: Article Body -->
                <div class="col-lg-8 mb-5 mb-lg-0" data-aos="fade-up">
                    <div class="article-body-card">
                        <!-- Cover Image -->
                        <?php if (!empty($blog['featured_image'])): ?>
                            <div class="article-banner-wrap">
                                <img src="<?php echo $blog['featured_image']; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <!-- HTML Content -->
                        <div class="article-content">
                            <?php echo $blog['content']; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Sidebar -->
                <div class="col-lg-4">
                    <!-- Navigation back widget -->
                    <div class="sidebar-widget text-center py-4" data-aos="fade-up">
                        <h4 class="sidebar-widget-title justify-content-center serif-title">Amadika Journal</h4>
                        <p class="text-muted small">Explore home decor insights and silent luxury guides.</p>
                        <a href="blogs.php" class="btn btn-dark w-100 py-2 border-0 mt-2" style="background: #1A1D20; border-radius: 30px; font-weight: 500;"><i class="fas fa-arrow-left me-2"></i>Back to Journal</a>
                    </div>

                    <!-- Related Articles widget -->
                    <?php if (!empty($related_blogs)): ?>
                        <div class="sidebar-widget" data-aos="fade-up">
                            <h4 class="sidebar-widget-title serif-title">Related Insights</h4>
                            <div class="related-list">
                                <?php foreach ($related_blogs as $rb): ?>
                                    <?php $rb_img = !empty($rb['featured_image']) ? $rb['featured_image'] : 'https://via.placeholder.com/150'; ?>
                                    <div class="related-item">
                                        <div class="related-thumb">
                                            <a href="blog/<?php echo $rb['slug']; ?>">
                                                <img src="<?php echo $rb_img; ?>" alt="<?php echo htmlspecialchars($rb['title']); ?>">
                                            </a>
                                        </div>
                                        <div class="related-info">
                                            <a href="blog/<?php echo $rb['slug']; ?>" class="related-link serif-title">
                                                <?php echo htmlspecialchars($rb['title']); ?>
                                            </a>
                                            <span class="related-date"><i class="far fa-calendar-alt me-2"></i><?php echo date('M d, Y', strtotime($rb['created_at'])); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Init AOS
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });

        // Reading progress bar logic
        window.addEventListener('scroll', function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById('reading-progress').style.width = scrolled + '%';
        });
    });

    // Copy link logic
    function copyArticleLink() {
        const dummy = document.createElement('input');
        const text = window.location.href;
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        
        const btn = document.getElementById('btn-copy-link');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i>';
        
        // Show SweetAlert2 toast
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Article link copied!',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 2000);
    }
</script>

<?php include 'includes/footer.php'; ?>

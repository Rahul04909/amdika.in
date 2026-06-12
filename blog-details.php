<?php
require_once 'database/db_config.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (empty($slug)) {
    header("Location: " . $link_prefix . "blogs.php");
    exit;
}

// Fetch blog post
$stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'active'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

if (!$blog) {
    // 404 response
    header("HTTP/1.1 404 Not Found");
    $page_title = 'Article Not Found - Amadika Premium';
    include 'includes/header.php';
    ?>
    <div class="container py-5 text-center" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <i class="far fa-frown fs-1 text-muted mb-3 opacity-50"></i>
        <h2 class="h3 text-secondary mb-2">Article Not Found</h2>
        <p class="text-muted">The blog post you are looking for does not exist or has been removed.</p>
        <a href="<?php echo $link_prefix; ?>blogs.php" class="btn btn-dark mt-3 px-4">Back to Blogs</a>
    </div>
    <?php
    include 'includes/footer.php';
    exit;
}

// Set SEO Meta Parameters for includes/header.php
$page_title = !empty($blog['seo_title']) ? $blog['seo_title'] : $blog['title'] . ' - Amadika Premium';
$page_description = !empty($blog['seo_description']) ? $blog['seo_description'] : substr(strip_tags($blog['summary'] ?: $blog['content']), 0, 160);
$page_keywords = !empty($blog['seo_keywords']) ? $blog['seo_keywords'] : 'amadika, blog, luxury leather, ' . strtolower($blog['title']);

include 'includes/header.php';

// Fetch related articles (excluding current one)
$related_stmt = $conn->prepare("SELECT * FROM blogs WHERE status = 'active' AND id != ? ORDER BY created_at DESC LIMIT 3");
$related_stmt->bind_param("i", $blog['id']);
$related_stmt->execute();
$related_blogs = $related_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<style>
    body {
        background-color: #fafafb;
        font-family: 'Rubik', 'Outfit', sans-serif;
    }
    
    /* Blog Banner */
    .article-hero {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 50px 0;
    }
    .article-category {
        color: #D4A017;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        display: inline-block;
    }
    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2D3436;
        line-height: 1.25;
        margin-bottom: 20px;
    }
    .article-meta {
        font-size: 0.9rem;
        color: #636E72;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .article-meta i {
        color: #D4A017;
    }
    .article-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Featured Image */
    .article-banner-wrap {
        margin: 40px 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        max-height: 500px;
    }
    .article-banner-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Article Main Layout */
    .article-main {
        padding: 40px 0 80px;
    }
    .article-body-card {
        background: #fff;
        border-radius: 16px;
        padding: 40px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    
    /* Text Content Styling (WYSIWYG overrides) */
    .article-content {
        color: #2D3436;
        font-size: 1.1rem;
        line-height: 1.8;
    }
    .article-content p {
        margin-bottom: 25px;
    }
    .article-content h2, .article-content h3 {
        color: #2D3436;
        font-weight: 600;
        margin-top: 40px;
        margin-bottom: 20px;
    }
    .article-content h2 { font-size: 1.75rem; }
    .article-content h3 { font-size: 1.4rem; }
    
    .article-content blockquote {
        border-left: 4px solid #D4A017;
        padding-left: 20px;
        margin: 30px 0;
        font-style: italic;
        color: #636E72;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    .article-content ul, .article-content ol {
        margin-bottom: 25px;
        padding-left: 20px;
    }
    .article-content li {
        margin-bottom: 8px;
    }

    /* Side Sidebar Widgets */
    .sidebar-widget {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 30px;
    }
    .sidebar-widget-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2D3436;
        border-bottom: 2px solid #F1F2F6;
        padding-bottom: 12px;
        margin-bottom: 20px;
        position: relative;
    }
    .sidebar-widget-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: -2px;
        width: 40px; height: 2px;
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
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
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
        font-weight: 500;
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
        color: #636E72;
    }
    
    .btn-share {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F1F2F6;
        color: #2D3436;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-share:hover {
        background: #D4A017;
        color: #fff;
        transform: translateY(-2px);
    }
</style>

<!-- JSON-LD SEO Schema for BlogPosting -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "<?php echo htmlspecialchars($blog['title']); ?>",
  "image": "https://amadika.in/<?php echo htmlspecialchars($blog['featured_image'] ?: 'assets/images/amdika-logo.png'); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo htmlspecialchars($blog['author'] ?: 'Admin'); ?>"
  },
  "datePublished": "<?php echo date('c', strtotime($blog['created_at'])); ?>",
  "dateModified": "<?php echo date('c', strtotime($blog['updated_at'])); ?>",
  "description": "<?php echo htmlspecialchars($blog['summary'] ?: substr(strip_tags($blog['content']), 0, 150)); ?>"
}
</script>

<!-- Article Header Section -->
<section class="article-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <span class="article-category">Inspiration & Insights</span>
                <h1 class="article-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
                
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="article-meta">
                        <span><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                        <span><i class="far fa-user"></i> By <?php echo htmlspecialchars($blog['author'] ?: 'Admin'); ?></span>
                    </div>
                    
                    <!-- Sharing widget -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted me-2">Share:</span>
                        <a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://amadika.in/blog/' . $blog['slug']); ?>" target="_blank" class="btn-share"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://amadika.in/blog/' . $blog['slug']); ?>&text=<?php echo urlencode($blog['title']); ?>" target="_blank" class="btn-share"><i class="fab fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($blog['title'] . ' - https://amadika.in/blog/' . $blog['slug']); ?>" target="_blank" class="btn-share"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Article Section -->
<section class="article-main">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Article Body -->
            <div class="col-lg-7 mb-4">
                <div class="article-body-card">
                    <!-- Featured Image -->
                    <?php if (!empty($blog['featured_image'])): ?>
                        <div class="article-banner-wrap">
                            <img src="<?php echo $link_prefix . $blog['featured_image']; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <!-- HTML Content -->
                    <div class="article-content">
                        <?php echo $blog['content']; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar-widget text-center py-4">
                    <h4 class="sidebar-widget-title justify-content-center">Read Articles</h4>
                    <p class="text-muted small">Discover more design inspiration and lifestyle advice.</p>
                    <a href="<?php echo $link_prefix; ?>blogs.php" class="btn btn-dark btn-sm w-100 py-2"><i class="fas fa-arrow-left me-2"></i>Back to Blogs</a>
                </div>

                <!-- Related Articles widget -->
                <?php if (!empty($related_blogs)): ?>
                    <div class="sidebar-widget">
                        <h4 class="sidebar-widget-title">Related Insights</h4>
                        <div class="related-list">
                            <?php foreach ($related_blogs as $rb): ?>
                                <?php $rb_img = !empty($rb['featured_image']) ? $rb['featured_image'] : 'https://via.placeholder.com/150'; ?>
                                <div class="related-item">
                                    <div class="related-thumb">
                                        <a href="<?php echo $link_prefix; ?>blog/<?php echo $rb['slug']; ?>">
                                            <img src="<?php echo $link_prefix . $rb_img; ?>" alt="<?php echo htmlspecialchars($rb['title']); ?>">
                                        </a>
                                    </div>
                                    <div class="related-info">
                                        <a href="<?php echo $link_prefix; ?>blog/<?php echo $rb['slug']; ?>" class="related-link">
                                            <?php echo htmlspecialchars($rb['title']); ?>
                                        </a>
                                        <span class="related-date"><i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($rb['created_at'])); ?></span>
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

<?php include 'includes/footer.php'; ?>

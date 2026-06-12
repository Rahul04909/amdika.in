<?php
$page_title = 'The Amadika Journal - Insights & Inspiration';
require_once 'database/db_config.php';
include 'includes/header.php';

// --- Pagination & Search Logic ---
$per_page = 5; // Highlight 1 featured post + 4 grid posts per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "status = 'active'";
$params = [];
$types = "";

if (!empty($search)) {
    $where .= " AND (title LIKE ? OR summary LIKE ? OR content LIKE ?)";
    $s_param = "%$search%";
    $params[] = $s_param;
    $params[] = $s_param;
    $params[] = $s_param;
    $types .= "sss";
}

// Count total matching blogs
$count_sql = "SELECT COUNT(*) as total FROM blogs WHERE $where";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_items = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_items / $per_page);

// Fetch blogs
$sql = "SELECT * FROM blogs WHERE $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch latest 4 active blogs for sidebar
$recent_blogs = $conn->query("SELECT * FROM blogs WHERE status = 'active' ORDER BY created_at DESC LIMIT 4")->fetch_all(MYSQLI_ASSOC);
?>

<!-- AOS CSS Library -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    /* Premium Styling Overrides */
    .blog-page-wrapper {
        background-color: #fafbfc;
        font-family: 'Rubik', sans-serif;
        color: #2D3436;
        overflow-x: hidden;
    }
    
    .serif-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
    }

    /* Hero Section */
    .blog-hero {
        background: linear-gradient(135deg, #1A1D20 0%, #2D3436 100%);
        color: #fff;
        padding: 90px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid #D4A017;
    }
    .blog-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle, rgba(212,160,23,0.06) 0%, transparent 80%);
        pointer-events: none;
    }
    .blog-hero h1 {
        font-size: 3.5rem;
        letter-spacing: -0.5px;
        margin-bottom: 15px;
    }
    .blog-hero h1 span {
        color: #D4A017;
        font-style: italic;
    }
    .blog-hero p {
        font-size: 1.2rem;
        color: rgba(255,255,255,0.75);
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .blog-section {
        padding: 80px 0;
    }

    /* Featured Spotlights */
    .featured-blog-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        margin-bottom: 50px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.03);
    }
    .featured-blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(212,160,23,0.1);
        border-color: rgba(212,160,23,0.2);
    }
    .featured-img-wrap {
        height: 100%;
        min-height: 350px;
        overflow: hidden;
        position: relative;
    }
    .featured-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .featured-blog-card:hover .featured-img-wrap img {
        transform: scale(1.03);
    }
    .featured-body {
        padding: 45px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }
    .blog-tag {
        color: #D4A017;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 2px;
        margin-bottom: 15px;
        display: inline-block;
    }
    .featured-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1A1D20;
        line-height: 1.25;
        margin-bottom: 18px;
        text-decoration: none;
        transition: color 0.2s;
    }
    .featured-title:hover {
        color: #D4A017;
    }
    .blog-meta {
        font-size: 0.85rem;
        color: #747d8c;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid #f1f2f6;
        padding-bottom: 15px;
    }
    .blog-meta i {
        color: #D4A017;
    }
    .blog-excerpt {
        color: #57606f;
        line-height: 1.8;
        font-size: 1.05rem;
        margin-bottom: 30px;
    }
    
    .btn-read-more {
        display: inline-flex;
        align-items: center;
        color: #1A1D20;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s;
        gap: 8px;
    }
    .btn-read-more i {
        font-size: 0.85rem;
        transition: transform 0.2s;
    }
    .btn-read-more:hover {
        color: #D4A017;
    }
    .btn-read-more:hover i {
        transform: translateX(5px);
    }

    /* Grid Cards */
    .blog-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 8px 30px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(212,160,23,0.08);
        border-color: rgba(212,160,23,0.15);
    }
    .blog-img-box {
        height: 250px;
        overflow: hidden;
        position: relative;
    }
    .blog-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .blog-card:hover .blog-img-box img {
        transform: scale(1.04);
    }
    .blog-card-body {
        padding: 30px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .blog-card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1A1D20;
        line-height: 1.4;
        margin-bottom: 12px;
        text-decoration: none;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card-title:hover {
        color: #D4A017;
    }
    .blog-card-excerpt {
        color: #57606f;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 22px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Sidebar Widgets */
    .widget {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 8px 30px rgba(0,0,0,0.02);
        margin-bottom: 35px;
        transition: border-color 0.3s;
    }
    .widget:hover {
        border-color: rgba(212,160,23,0.1);
    }
    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1A1D20;
        border-bottom: 2px solid #F1F2F6;
        padding-bottom: 15px;
        margin-bottom: 22px;
        position: relative;
    }
    .widget-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: -2px;
        width: 45px; height: 2px;
        background-color: #D4A017;
    }

    .recent-post-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid #F1F2F6;
    }
    .recent-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .recent-post-thumb {
        width: 75px;
        height: 75px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        border: 1px solid #eee;
    }
    .recent-post-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .recent-post-info {
        flex-grow: 1;
    }
    .recent-post-link {
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
    .recent-post-link:hover {
        color: #D4A017;
    }
    .recent-post-date {
        font-size: 0.8rem;
        color: #747d8c;
    }

    /* Search Input Widget */
    .search-widget .input-group {
        border-radius: 35px;
        overflow: hidden;
        border: 1.5px solid #e1e2e6;
        background: #fff;
        padding: 2px;
        transition: all 0.3s;
    }
    .search-widget .input-group:focus-within {
        border-color: #D4A017;
        box-shadow: 0 0 0 3px rgba(212,160,23,0.1);
    }
    .search-widget .form-control {
        border: none !important;
        box-shadow: none !important;
        padding-left: 20px;
        font-size: 0.95rem;
    }
    .search-widget button {
        border-radius: 35px !important;
        background-color: #D4A017 !important;
        border: none !important;
        color: #fff !important;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }
    .search-widget button:hover {
        background-color: #B8860B !important;
    }

    /* Pagination */
    .pagination-custom .page-link {
        border-radius: 50% !important;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
        border: 1px solid #e1e2e6;
        color: #2D3436;
        font-weight: 600;
        transition: all 0.3s;
    }
    .pagination-custom .page-item.active .page-link {
        background-color: #D4A017;
        border-color: #D4A017;
        color: #fff;
        box-shadow: 0 4px 15px rgba(212,160,23,0.25);
    }
    .pagination-custom .page-link:hover {
        background-color: #1A1D20;
        border-color: #1A1D20;
        color: #fff;
    }
</style>

<div class="blog-page-wrapper">
    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1 class="serif-title">The Amadika <span>Journal</span></h1>
            <p>Curated insights into timeless leather craftsmanship, premium interior decor, and silent luxury living.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="blog-section">
        <div class="container">
            <div class="row">
                <!-- Left: Articles list -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <?php if (empty($blogs)): ?>
                        <div class="text-center py-5" data-aos="fade-up">
                            <i class="far fa-newspaper fs-1 text-muted mb-4 opacity-50"></i>
                            <h3 class="h4 serif-title text-secondary mb-2">No Articles Found</h3>
                            <p class="text-muted">We couldn't find any articles matching "<?php echo htmlspecialchars($search); ?>". Please try a different query.</p>
                            <a href="blogs.php" class="btn btn-dark mt-3 px-4 py-2 border-0" style="background: #D4A017; border-radius: 30px;">Browse All Articles</a>
                        </div>
                    <?php else: ?>
                        
                        <!-- Featured Article Spotlight (on Page 1, without search query) -->
                        <?php if ($page === 1 && empty($search)): ?>
                            <?php 
                            $featured = array_shift($blogs); 
                            $feat_img = !empty($featured['featured_image']) ? $featured['featured_image'] : 'https://via.placeholder.com/800x450';
                            ?>
                            <div class="featured-blog-card" data-aos="fade-up">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="featured-img-wrap">
                                            <a href="blog/<?php echo $featured['slug']; ?>">
                                                <img src="<?php echo $feat_img; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="featured-body h-100">
                                            <span class="blog-tag">Featured Insight</span>
                                            <a href="blog/<?php echo $featured['slug']; ?>" class="featured-title serif-title">
                                                <?php echo htmlspecialchars($featured['title']); ?>
                                            </a>
                                            <div class="blog-meta">
                                                <span><i class="far fa-calendar-alt me-2"></i><?php echo date('M d, Y', strtotime($featured['created_at'])); ?></span>
                                                <span><i class="far fa-user me-2"></i>By <?php echo htmlspecialchars($featured['author'] ?: 'Admin'); ?></span>
                                            </div>
                                            <p class="blog-excerpt">
                                                <?php echo htmlspecialchars($featured['summary'] ?: substr(strip_tags($featured['content']), 0, 150) . '...'); ?>
                                            </p>
                                            <div>
                                                <a href="blog/<?php echo $featured['slug']; ?>" class="btn-read-more">
                                                    Read Article <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Cards Grid -->
                        <div class="row g-4">
                            <?php foreach ($blogs as $b): ?>
                                <?php $card_img = !empty($b['featured_image']) ? $b['featured_image'] : 'https://via.placeholder.com/600x400'; ?>
                                <div class="col-md-6" data-aos="fade-up">
                                    <div class="blog-card">
                                        <div class="blog-img-box">
                                            <a href="blog/<?php echo $b['slug']; ?>">
                                                <img src="<?php echo $card_img; ?>" alt="<?php echo htmlspecialchars($b['title']); ?>">
                                            </a>
                                        </div>
                                        <div class="blog-card-body">
                                            <span class="blog-tag">Insight</span>
                                            <a href="blog/<?php echo $b['slug']; ?>" class="blog-card-title serif-title">
                                                <?php echo htmlspecialchars($b['title']); ?>
                                            </a>
                                            <div class="blog-meta mb-3" style="border:none; padding:0; margin:0;">
                                                <span><i class="far fa-calendar-alt me-2"></i><?php echo date('M d, Y', strtotime($b['created_at'])); ?></span>
                                            </div>
                                            <p class="blog-card-excerpt">
                                                <?php echo htmlspecialchars($b['summary'] ?: substr(strip_tags($b['content']), 0, 110) . '...'); ?>
                                            </p>
                                            <div class="mt-auto">
                                                <a href="blog/<?php echo $b['slug']; ?>" class="btn-read-more">
                                                    Read Article <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <nav class="mt-5" data-aos="fade-up">
                                <ul class="pagination pagination-custom justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Right: Sidebar -->
                <div class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="widget search-widget" data-aos="fade-up">
                        <h4 class="widget-title serif-title">Search Articles</h4>
                        <form method="GET" action="blogs.php" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search keywords..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Recent Posts -->
                    <?php if (!empty($recent_blogs)): ?>
                        <div class="widget" data-aos="fade-up">
                            <h4 class="widget-title serif-title">Recent Insights</h4>
                            <div class="recent-posts-list">
                                <?php foreach ($recent_blogs as $rb): ?>
                                    <?php $rb_img = !empty($rb['featured_image']) ? $rb['featured_image'] : 'https://via.placeholder.com/150'; ?>
                                    <div class="recent-post-item">
                                        <div class="recent-post-thumb">
                                            <a href="blog/<?php echo $rb['slug']; ?>">
                                                <img src="<?php echo $rb_img; ?>" alt="<?php echo htmlspecialchars($rb['title']); ?>">
                                            </a>
                                        </div>
                                        <div class="recent-post-info">
                                            <a href="blog/<?php echo $rb['slug']; ?>" class="recent-post-link serif-title">
                                                <?php echo htmlspecialchars($rb['title']); ?>
                                            </a>
                                            <span class="recent-post-date"><i class="far fa-calendar-alt me-2"></i><?php echo date('M d, Y', strtotime($rb['created_at'])); ?></span>
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

<!-- AOS JS Library -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });
    });
</script>

<?php include 'includes/footer.php'; ?>

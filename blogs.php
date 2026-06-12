<?php
$page_title = 'Blogs & Insights - Amadika Premium';
require_once 'database/db_config.php';
include 'includes/header.php';

// --- Pagination & Search Logic ---
$per_page = 6;
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

<style>
    body {
        background-color: #fcfcfd;
        font-family: 'Rubik', 'Outfit', sans-serif;
    }
    
    /* Hero Header */
    .blog-hero {
        background: linear-gradient(135deg, #1A1D20 0%, #2D3436 100%);
        color: #fff;
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .blog-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle, rgba(212,160,23,0.05) 0%, transparent 80%);
        pointer-events: none;
    }
    .blog-hero h1 {
        font-weight: 700;
        font-size: 3rem;
        letter-spacing: -1px;
    }
    .blog-hero h1 span {
        color: #D4A017;
    }
    .blog-hero p {
        font-size: 1.15rem;
        color: rgba(255,255,255,0.7);
        max-width: 600px;
        margin: 15px auto 0;
    }

    /* Listing Section */
    .blog-section {
        padding: 60px 0;
    }

    /* Featured Post Card */
    .featured-blog-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        margin-bottom: 40px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .featured-blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(212,160,23,0.08);
    }
    .featured-img-wrap {
        height: 400px;
        overflow: hidden;
    }
    .featured-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .featured-blog-card:hover .featured-img-wrap img {
        transform: scale(1.03);
    }
    .featured-body {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .blog-tag {
        color: #D4A017;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        display: inline-block;
    }
    .featured-title {
        font-size: 2rem;
        font-weight: 600;
        color: #2D3436;
        line-height: 1.3;
        margin-bottom: 15px;
        text-decoration: none;
        transition: color 0.2s;
    }
    .featured-title:hover {
        color: #D4A017;
    }
    .blog-meta {
        font-size: 0.85rem;
        color: #636E72;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .blog-meta i {
        color: #D4A017;
    }
    .blog-excerpt {
        color: #636E72;
        line-height: 1.7;
        font-size: 1rem;
        margin-bottom: 25px;
    }
    .btn-read-more {
        display: inline-flex;
        align-items: center;
        color: #2D3436;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.2s, gap 0.2s;
        gap: 8px;
    }
    .btn-read-more:hover {
        color: #D4A017;
        gap: 12px;
    }

    /* Standard Cards */
    .blog-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(212,160,23,0.08);
    }
    .blog-img-box {
        height: 230px;
        overflow: hidden;
        position: relative;
    }
    .blog-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .blog-card:hover .blog-img-box img {
        transform: scale(1.04);
    }
    .blog-card-body {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .blog-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2D3436;
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
        color: #636E72;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Sidebar Widgets */
    .widget {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 30px;
    }
    .widget-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #2D3436;
        border-bottom: 2px solid #F1F2F6;
        padding-bottom: 12px;
        margin-bottom: 20px;
        position: relative;
    }
    .widget-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: -2px;
        width: 40px; height: 2px;
        background-color: #D4A017;
    }
    .recent-post-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #F1F2F6;
    }
    .recent-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .recent-post-thumb {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
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
        font-size: 0.9rem;
        font-weight: 500;
        color: #2D3436;
        text-decoration: none;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 4px;
        transition: color 0.2s;
    }
    .recent-post-link:hover {
        color: #D4A017;
    }
    .recent-post-date {
        font-size: 0.75rem;
        color: #636E72;
    }

    /* Category Tag Cloud */
    .tag-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tag-cloud-item {
        font-size: 0.8rem;
        background: #F1F2F6;
        color: #2D3436;
        padding: 6px 12px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    .tag-cloud-item:hover {
        background: #D4A017;
        color: #fff;
    }

    /* Search Widget */
    .search-widget .form-control {
        border-radius: 30px;
        padding: 10px 20px;
        border: 1px solid #ddd;
    }
    .search-widget .form-control:focus {
        border-color: #D4A017;
        box-shadow: 0 0 0 3px rgba(212,160,23,0.15);
    }
    .search-widget button {
        border-radius: 30px;
        padding: 10px 20px;
        background-color: #D4A017;
        border: none;
        color: #fff;
        transition: background-color 0.2s;
    }
    .search-widget button:hover {
        background-color: #B8860B;
    }

    /* Pagination */
    .pagination-custom .page-link {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
        border: 1px solid #eee;
        color: #2D3436;
        font-weight: 500;
        transition: all 0.2s;
    }
    .pagination-custom .page-item.active .page-link {
        background-color: #D4A017;
        border-color: #D4A017;
        color: #fff;
    }
    .pagination-custom .page-link:hover {
        background-color: #F1F2F6;
        color: #D4A017;
    }
</style>

<!-- Hero Section -->
<section class="blog-hero">
    <div class="container">
        <h1>Amadika <span>Editorial</span></h1>
        <p>Your premium resource for interior styling advice, fine leather craftsmanship insights, and home lifestyle trends.</p>
    </div>
</section>

<!-- Main Listing Section -->
<section class="blog-section">
    <div class="container">
        <div class="row">
            <!-- Left: Articles list -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <?php if (empty($blogs)): ?>
                    <div class="text-center py-5">
                        <i class="far fa-newspaper fs-1 text-muted mb-3 opacity-50"></i>
                        <h3 class="h4 text-secondary mb-2">No Articles Found</h3>
                        <p class="text-muted">We couldn't find any articles matching "<?php echo htmlspecialchars($search); ?>". Please try a different search or browse all articles.</p>
                        <a href="blogs.php" class="btn btn-outline-dark mt-3 px-4">Browse All Articles</a>
                    </div>
                <?php else: ?>
                    <!-- Spotlighting Featured Article on Page 1 (when not filtering by search) -->
                    <?php if ($page === 1 && empty($search)): ?>
                        <?php 
                        $featured = array_shift($blogs); 
                        $feat_img = !empty($featured['featured_image']) ? $featured['featured_image'] : 'https://via.placeholder.com/800x450';
                        ?>
                        <div class="featured-blog-card">
                            <div class="row g-0">
                                <div class="col-md-12 col-lg-6">
                                    <div class="featured-img-wrap">
                                        <a href="blog/<?php echo $featured['slug']; ?>">
                                            <img src="<?php echo $feat_img; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="featured-body h-100">
                                        <span class="blog-tag">Featured Post</span>
                                        <a href="blog/<?php echo $featured['slug']; ?>" class="featured-title">
                                            <?php echo htmlspecialchars($featured['title']); ?>
                                        </a>
                                        <div class="blog-meta">
                                            <span><i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($featured['created_at'])); ?></span>
                                            <span><i class="far fa-user me-1"></i> <?php echo htmlspecialchars($featured['author'] ?: 'Admin'); ?></span>
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
                            <div class="col-md-6">
                                <div class="blog-card">
                                    <div class="blog-img-box">
                                        <a href="blog/<?php echo $b['slug']; ?>">
                                            <img src="<?php echo $card_img; ?>" alt="<?php echo htmlspecialchars($b['title']); ?>">
                                        </a>
                                    </div>
                                    <div class="blog-card-body">
                                        <span class="blog-tag">Articles</span>
                                        <a href="blog/<?php echo $b['slug']; ?>" class="blog-card-title">
                                            <?php echo htmlspecialchars($b['title']); ?>
                                        </a>
                                        <div class="blog-meta mb-3">
                                            <span><i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($b['created_at'])); ?></span>
                                        </div>
                                        <p class="blog-card-excerpt">
                                            <?php echo htmlspecialchars($b['summary'] ?: substr(strip_tags($b['content']), 0, 100) . '...'); ?>
                                        </p>
                                        <div class="mt-auto">
                                            <a href="blog/<?php echo $b['slug']; ?>" class="btn-read-more">
                                                Read More <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav class="mt-5">
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
                <div class="widget search-widget">
                    <h4 class="widget-title">Search Articles</h4>
                    <form method="GET" action="blogs.php" class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Type keywords..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Recent Posts -->
                <?php if (!empty($recent_blogs)): ?>
                    <div class="widget">
                        <h4 class="widget-title">Recent Insights</h4>
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
                                        <a href="blog/<?php echo $rb['slug']; ?>" class="recent-post-link">
                                            <?php echo htmlspecialchars($rb['title']); ?>
                                        </a>
                                        <span class="recent-post-date"><i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($rb['created_at'])); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Categories / Tag Cloud -->
                <div class="widget">
                    <h4 class="widget-title">Categories</h4>
                    <div class="tag-cloud">
                        <a href="#" class="tag-cloud-item">Leather Craft</a>
                        <a href="#" class="tag-cloud-item">Premium Styling</a>
                        <a href="#" class="tag-cloud-item">Home Decor</a>
                        <a href="#" class="tag-cloud-item">Living Trends</a>
                        <a href="#" class="tag-cloud-item">Handmade Luxury</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

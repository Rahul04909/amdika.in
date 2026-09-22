<?php
header("Content-Type: application/xml; charset=utf-8");

// Try to connect using default db_config.php first
$db_connected = false;
try {
    @include 'database/db_config.php';
    if (isset($conn) && !$conn->connect_error) {
        $db_connected = true;
    }
} catch (Exception $e) {
    // ignore
}

// Fallback credentials for local testing
if (!$db_connected) {
    $creds = [
        ['localhost', 'root', '', 'amadik_ecom'],
        ['localhost', 'root', '', 'amadika']
    ];
    foreach ($creds as $c) {
        try {
            $conn = @new mysqli($c[0], $c[1], $c[2], $c[3]);
            if ($conn && !$conn->connect_error) {
                $db_connected = true;
                break;
            }
        } catch (Exception $e) {
            // continue
        }
    }
}

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];

// Get base directory of the site (e.g. /amadika/ or /)
$script = $_SERVER['SCRIPT_NAME'];
$base_dir = dirname($script);
if ($base_dir === DIRECTORY_SEPARATOR || $base_dir === '\\' || $base_dir === '/') {
    $base_dir = '/';
} else {
    $base_dir = rtrim(str_replace('\\', '/', $base_dir), '/') . '/';
}

$site_url = $protocol . "://" . $host . $base_dir;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc><?php echo $site_url; ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>products.php</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>blogs.php</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/about-us/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/contact-us/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/privacy-policy/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/terms-and-conditions/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/visit-us/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/shipping-policy/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/fees-and-payments/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc><?php echo $site_url; ?>pages/warrnty-retrun-refund-policy/index.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    <?php
    if ($db_connected && $conn) {
        // Fetch Categories
        $cat_res = $conn->query("SELECT slug FROM product_categories ORDER BY id DESC");
        if ($cat_res) {
            while ($cat = $cat_res->fetch_assoc()) {
                if (!empty($cat['slug'])) {
                    $cat_url = $site_url . "products.php?category=" . urlencode($cat['slug']);
                    echo "    <url>\n";
                    echo "        <loc>" . htmlspecialchars($cat_url) . "</loc>\n";
                    echo "        <changefreq>weekly</changefreq>\n";
                    echo "        <priority>0.7</priority>\n";
                    echo "    </url>\n";
                }
            }
        }

        // Fetch Products
        $prod_res = $conn->query("SELECT slug FROM products WHERE status = 'active' ORDER BY id DESC");
        if ($prod_res) {
            while ($prod = $prod_res->fetch_assoc()) {
                if (!empty($prod['slug'])) {
                    $prod_url = $site_url . "product/" . $prod['slug'];
                    echo "    <url>\n";
                    echo "        <loc>" . htmlspecialchars($prod_url) . "</loc>\n";
                    echo "        <changefreq>weekly</changefreq>\n";
                    echo "        <priority>0.8</priority>\n";
                    echo "    </url>\n";
                }
            }
        }

        // Fetch Blogs
        $blog_res = $conn->query("SELECT slug, updated_at FROM blogs WHERE status = 'active' ORDER BY id DESC");
        if ($blog_res) {
            while ($blog = $blog_res->fetch_assoc()) {
                if (!empty($blog['slug'])) {
                    $blog_url = $site_url . "blog/" . $blog['slug'];
                    $lastmod = !empty($blog['updated_at']) ? date('Y-m-d', strtotime($blog['updated_at'])) : date('Y-m-d');
                    echo "    <url>\n";
                    echo "        <loc>" . htmlspecialchars($blog_url) . "</loc>\n";
                    echo "        <lastmod>" . $lastmod . "</lastmod>\n";
                    echo "        <changefreq>weekly</changefreq>\n";
                    echo "        <priority>0.7</priority>\n";
                    echo "    </url>\n";
                }
            }
        }
    }
    ?>
</urlset>

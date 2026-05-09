<?php
require_once '../database/db_config.php';

echo "<html><head><title>Deleted Products Photo Gallery</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head>";
echo "<body class='bg-light'><div class='container py-5'>";
echo "<h2 class='mb-4'>Deleted Products Photo Gallery</h2>";
echo "<p class='text-muted'>These images exist on your server but don't belong to any product in your database. Use these to identify what was deleted.</p>";
echo "<div class='row g-4'>";

$dir = "../assets/images/products/";
if (is_dir($dir)) {
    $files = scandir($dir);
    $count = 0;
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        if (is_dir($dir.$file)) continue;

        $path = "assets/images/products/" . $file;
        $check = $conn->query("SELECT id FROM products WHERE featured_image = '$path'");
        
        if ($check && $check->num_rows == 0) {
            echo "<div class='col-md-3'>";
            echo "<div class='card h-100 shadow-sm'>";
            echo "<img src='../$path' class='card-img-top' style='height: 200px; object-fit: cover;'>";
            echo "<div class='card-body p-2 text-center'>";
            echo "<small class='text-truncate d-block'>$file</small>";
            echo "</div></div></div>";
            $count++;
        }
    }
    if ($count == 0) echo "<div class='col-12'><p class='alert alert-info'>No orphaned images found.</p></div>";
}

echo "</div></div></body></html>";
?>

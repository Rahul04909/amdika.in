<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$promo = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM category_promos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $promo = $stmt->get_result()->fetch_assoc();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = intval($_POST['category_id']);
    $type = $_POST['type'];
    $link_url = $_POST['link_url'];
    $status = $_POST['status'];
    $media_path = $_POST['media_path_existing'] ?? '';
    $thumbnail = $_POST['thumbnail_existing'] ?? '';

    // Handle File Upload for Image or Thumbnail
    if ($type == 'image' && isset($_FILES['media_image']) && $_FILES['media_image']['error'] == 0) {
        $target_dir = "../../assets/images/banners/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . '_' . basename($_FILES["media_image"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["media_image"]["tmp_name"], $target_file)) {
            $media_path = "assets/images/banners/" . $file_name;
        }
    } elseif ($type == 'video') {
        $media_path = $_POST['youtube_url'];
        if (isset($_FILES['video_thumbnail']) && $_FILES['video_thumbnail']['error'] == 0) {
            $target_dir = "../../assets/images/banners/";
            $file_name = "thumb_" . time() . '_' . basename($_FILES["video_thumbnail"]["name"]);
            $target_file = $target_dir . $file_name;
            if (move_uploaded_file($_FILES["video_thumbnail"]["tmp_name"], $target_file)) {
                $thumbnail = "assets/images/banners/" . $file_name;
            }
        }
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE category_promos SET category_id=?, type=?, media_path=?, thumbnail=?, link_url=?, status=? WHERE id=?");
        $stmt->bind_param("isssssi", $category_id, $type, $media_path, $thumbnail, $link_url, $status, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO category_promos (category_id, type, media_path, thumbnail, link_url, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $category_id, $type, $media_path, $thumbnail, $link_url, $status);
    }

    if ($stmt->execute()) {
        header("Location: manage-category-promos.php?success=saved");
    } else {
        $error = "Error saving promo: " . $stmt->error;
    }
}

$categories = $conn->query("SELECT id, name FROM product_categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$page_title = ($id > 0 ? 'Edit' : 'Add') . ' Category Promo';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Amadika Admin</title>
    <link href="../../assets/vendor/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #D32F2F; --body-bg: #f5f7fa; }
        body { font-family: 'Rubik', sans-serif; background-color: var(--body-bg); }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; padding: 30px; }
    </style>
</head>
<body>
    <div class="d-flex wrapper" id="wrapper">
        <?php include '../../admin/includes/sidebar.php'; ?>
        <div id="page-content-wrapper" style="width: 100%;">
            <?php include '../../admin/includes/header.php'; ?>
            <div class="container-fluid px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-secondary mb-0"><?php echo $page_title; ?></h2>
                    <a href="manage-category-promos.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>

                <?php if(isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

                <div class="card card-custom">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Select Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Choose Category...</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo ($promo && $promo['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Promo Type</label>
                                <select name="type" id="typeSelect" class="form-select" onchange="toggleInputs()">
                                    <option value="image" <?php echo ($promo && $promo['type'] == 'image') ? 'selected' : ''; ?>>Image Banner</option>
                                    <option value="video" <?php echo ($promo && $promo['type'] == 'video') ? 'selected' : ''; ?>>YouTube Video</option>
                                </select>
                            </div>

                            <!-- Image Fields -->
                            <div class="col-12 mb-3" id="imageInput" style="<?php echo ($promo && $promo['type'] == 'video') ? 'display:none;' : ''; ?>">
                                <label class="form-label fw-bold">Promo Banner Image</label>
                                <input type="file" name="media_image" class="form-control">
                                <input type="hidden" name="media_path_existing" value="<?php echo $promo['media_path'] ?? ''; ?>">
                                <?php if($promo && $promo['type'] == 'image'): ?>
                                    <img src="../../<?php echo $promo['media_path']; ?>" class="mt-2 rounded" style="height: 100px;">
                                <?php endif; ?>
                            </div>

                            <!-- Video Fields -->
                            <div id="videoInputs" style="<?php echo (!$promo || $promo['type'] == 'image') ? 'display:none;' : ''; ?>">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">YouTube Embed URL</label>
                                    <input type="text" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/embed/..." value="<?php echo $promo['media_path'] ?? ''; ?>">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Video Thumbnail (Banner Size)</label>
                                    <input type="file" name="video_thumbnail" class="form-control">
                                    <input type="hidden" name="thumbnail_existing" value="<?php echo $promo['thumbnail'] ?? ''; ?>">
                                    <?php if($promo && $promo['type'] == 'video'): ?>
                                        <img src="../../<?php echo $promo['thumbnail']; ?>" class="mt-2 rounded" style="height: 100px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Redirect Link URL</label>
                                <input type="text" name="link_url" class="form-control" value="<?php echo $promo['link_url'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?php echo ($promo && $promo['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($promo && $promo['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-danger px-5 py-2 fw-bold">SAVE PROMO</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleInputs() {
            const type = document.getElementById('typeSelect').value;
            document.getElementById('imageInput').style.display = (type === 'image') ? 'block' : 'none';
            document.getElementById('videoInputs').style.display = (type === 'video') ? 'block' : 'none';
        }
    </script>
</body>
</html>

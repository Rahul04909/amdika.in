<?php
$page_title       = "Collections — Amadika | Premium Leather Goods";
$page_description = "Explore the Amadika Collection — premium handcrafted leather home accessories, desk organisers, trunk boxes and more.";
include '../../includes/header.php';
?>

<style>
.collection-hero {
    width: 100%;
    overflow: hidden;
    line-height: 0;
}

.collection-hero img {
    display: block;
    width: 100%;
    height: auto;
    object-fit: cover;
}

@media (max-width: 768px) {
    .collection-hero img {
        min-height: 200px;
        object-fit: cover;
    }
}
</style>

<section class="collection-hero">
    <img
        src="<?php echo $assets_path; ?>images/collection/banner%20amadika.png"
        alt="Amadika Collection"
        loading="eager"
        decoding="async">
</section>
<?php include '../../components/fitness-showcase.php'; ?>

<?php include '../../includes/footer.php'; ?>

</body>
</html>

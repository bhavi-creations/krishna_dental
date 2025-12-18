<?php
include './db.connection/db_connection.php';

// Get service filter
$service = isset($_GET['service']) ? trim($_GET['service']) : '';

// Base SQL
$sql = "SELECT id, title, main_content, main_image, created_at FROM blogs";
if (!empty($service)) {
    $sql .= " WHERE service = ?";
}
$sql .= " ORDER BY created_at DESC";

// Prepare
$stmt = $conn->prepare($sql);

// Bind if filter exists
if (!empty($service)) {
    $stmt->bind_param("s", $service);
}

// Execute
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'header.php'; ?>

<!-- Breadcrumb -->
<div class="breadcumb-wrapper">
  <div class="parallax" data-parallax-image="assets/img/about/krishnadentaure_blogs_page.png"></div>
  <div class="container z-index-common">
    <div class="breadcumb-content">
      <h1 class="breadcumb-title">Blogs</h1>
      <div class="breadcumb-menu-wrap">
        <i class="far fa-home-lg"></i>
        <ul class="breadcumb-menu">
          <li><a href="index.php">Home</a></li>
          <li class="active">Blogs</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<main>

<!-- Filter Buttons -->
<div class="container mt-5">
  <div class="filter_buttons redirect_section">

    <a href="blogs.php"><button class="redirect_blog_srivice">All</button></a>
    <a href="blogs.php?service=Root Canal"><button class="redirect_blog_srivice">Root Canal</button></a>
    <a href="blogs.php?service=Wisdom Tooth Removal"><button class="redirect_blog_srivice">Wisdom Tooth Removal</button></a>
    <a href="blogs.php?service=Bad Breath Treatment"><button class="redirect_blog_srivice">Bad Breath Treatment</button></a>
    <a href="blogs.php?service=Gum Treatment"><button class="redirect_blog_srivice">Gum Treatment</button></a>
    <a href="blogs.php?service=Teeth Cleaning"><button class="redirect_blog_srivice">Teeth Cleaning</button></a>
    <a href="blogs.php?service=Orthodontic Treatment"><button class="redirect_blog_srivice">Orthodontic Treatment</button></a>
    <a href="blogs.php?service=Dental Crown & Bridge"><button class="redirect_blog_srivice">Dental Crown & Bridge</button></a>
    <a href="blogs.php?service=Invisible Aligners"><button class="redirect_blog_srivice">Invisible Aligners</button></a>
    <a href="blogs.php?service=Dental Veneers"><button class="redirect_blog_srivice">Dental Veneers</button></a>
    <a href="blogs.php?service=Smile Makeover"><button class="redirect_blog_srivice">Smile Makeover</button></a>
    <a href="blogs.php?service=Teeth Whitening"><button class="redirect_blog_srivice">Teeth Whitening</button></a>
    <a href="blogs.php?service=Dental Implant"><button class="redirect_blog_srivice">Dental Implant</button></a>
    <a href="blogs.php?service=Dentures"><button class="redirect_blog_srivice">Dentures</button></a>
    <a href="blogs.php?service=Fluoride Application & Dental Sealant">
      <button class="redirect_blog_srivice">Fluoride Application & Dental Sealant</button>
    </a>
    <a href="blogs.php?service=Full Mouth Rehabilitation Treatment">
      <button class="redirect_blog_srivice">Full Mouth Rehabilitation Treatment</button>
    </a>

  </div>
</div>

<!-- Blog List -->
<div class="container blog-sidebar-list" style="padding: 20px 0;">
  <div class="row">
    <div class="col-lg-12">
      <div class="row">

<?php
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        // IMAGE FIX
        $upload_dir = "./admin/uploads/photos/";
        $default_image = "./assets/img/default-blog.jpg";

        if (!empty($row['main_image']) && file_exists($upload_dir . $row['main_image'])) {
            $image_path = $upload_dir . $row['main_image'];
        } else {
            $image_path = $default_image;
        }
?>
        <div class="col-sm-12 col-lg-4 mb-5">
          <div class="post-box card_bg_div_box">

            <figure>
              <a href="fullblog.php?id=<?= $row['id']; ?>">
                <img src="<?= $image_path; ?>" class="img-fluid blog_box_image" alt="Blog Image">
              </a>
            </figure>

            <div class="box-content">
              <h5 class="box-title">
                <a href="fullblog.php?id=<?= $row['id']; ?>">
                  <?= htmlspecialchars($row['title']); ?>
                </a>
              </h5>

              <p class="post-desc mt-3" style="text-align: justify;">
                <?= substr(strip_tags($row['main_content']), 0, 90); ?>...
              </p>

              <a href="fullblog.php?id=<?= $row['id']; ?>">
                <button class="blog_main_btn">Read More..</button>
              </a>
            </div>

          </div>
        </div>
<?php
    }
} else {
    echo "<p class='text-center'>No blog posts found.</p>";
}
?>

      </div>
    </div>
  </div>
</div>

</main>

<?php include 'footer.php'; ?>

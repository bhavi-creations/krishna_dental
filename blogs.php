<?php
include './db.connection/db_connection.php';

// Service filter
$service = isset($_GET['service']) ? $_GET['service'] : '';

// Query
$sql = "SELECT id, title, main_content, main_image, created_at FROM blogs";
if (!empty($service)) {
  $sql .= " WHERE service = ?";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

// Check if SQL prepare failed to prevent fatal crash
if (!$stmt) {
  die("Database Query Error: " . $conn->error);
}

if (!empty($service)) {
  $stmt->bind_param("s", $service);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'header.php'; ?>

<style>
  .post-box {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .box-content {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .post-desc {
    flex-grow: 1;
  }

  .blog-date {
    margin-top: 10px;
    font-size: 13px;
    background: #000;
    color: #fff;
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 600;
  }

.blog_section_stylings{
  background: radial-gradient(circle at 90% 0%, rgba(80, 223, 255, .15), transparent 34%), linear-gradient(115deg, #0878b8 0%, #075f9d 52%, #045188 100%) !important;

}

</style>

<main class="blog_section_stylings">
  <div class="container blog-sidebar-list" style="padding-top: 20px; padding-bottom: 20px;">
    <div class="row">
      <div class="col-lg-12">
        <div class="grid row">

          <?php
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

              // ✅ Image path
              $image_path = !empty($row['main_image'])
                ? "admin/uploads/photos/" . htmlspecialchars($row['main_image'])
                : "default_image.png";

              // ✅ SEO URL (slug)
              $blog_link_val = !empty($row['slug']) ? urlencode($row['slug']) : $row['id'];
              $final_url = "fullblog.php?id=" . $blog_link_val;

              // ✅ Date format
              $formatted_date = date("d M Y, h:i A", strtotime($row['created_at']));

              // ✅ Safe preview (Quill content → text)
              $preview = substr(strip_tags(html_entity_decode($row['main_content'])), 0, 100);

              echo "
              <div class='grid-item col-sm-12 col-lg-4 mb-5'>
                  <div class='post-box card_bg_div_box'>
                      <figure>
                          <a href='{$final_url}'>
                              <img src='{$image_path}' alt='Blog Image' class='img-fluid blog_box_image'>
                          </a>
                      </figure>

                      <div class='box-content'>
                          <h5 class='box-title'>
                              <a class='box-title' href='{$final_url}'>" . htmlspecialchars($row['title']) . "</a>
                          </h5>

                          <p class='post-desc mt-3' style='text-align: justify;'>
                              {$preview}...
                          </p>

                          <a href='{$final_url}'>
                              <button class='blog_main_btn'>Read More..</button>
                          </a>

                          <!-- ✅ FIXED DATE ICON -->
                          <p class='blog-date'>🕒 {$formatted_date}</p>
                      </div>
                  </div>
              </div>";
            }
          } else {
            echo "<p>No blog posts found.</p>";
          }
          ?>

        </div>
      </div>
    </div>
  </div>
</main>

<?php include('./footer.php'); ?>

<?php
if ($stmt && $stmt instanceof mysqli_stmt) {
    $stmt->close();
}
$conn->close();
?>
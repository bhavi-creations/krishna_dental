<?php
include './db.connection/db_connection.php';

// Service filter
$service = isset($_GET['service']) ? $_GET['service'] : '';

// Query
$sql = "SELECT id, title, slug, main_content, main_image, created_at FROM blogs";
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
  /* 1. Main Container Background Theme */
  main.blog_section_stylings {
    background: radial-gradient(circle at 90% 0%, rgba(80, 223, 255, .18), transparent 35%), 
                linear-gradient(115deg, #0878b8 0%, #075f9d 52%, #045188 100%) !important;
    padding: 60px 0 !important;
    color: #ffffff !important;
    min-height: 100vh;
  }

  /* 2. Blog Card Styling (Cyan Curved Stroke Line & Translucent BG) */
  main.blog_section_stylings .post-box.card_bg_div_box {
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    background: rgba(4, 51, 88, 0.75) !important; /* Premium dark blue backdrop */
    border: 1px solid rgba(80, 223, 255, 0.5) !important; /* Cyan curve stroke line */
    border-radius: 12px !important;
    padding: 20px !important;
    backdrop-filter: blur(10px) !important;
    -webkit-backdrop-filter: blur(10px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3), 0 0 15px rgba(80, 223, 255, 0.2) !important;
    transition: all 0.3s ease-in-out !important;
  }

  /* Card Hover Glow Effect */
  main.blog_section_stylings .post-box.card_bg_div_box:hover {
    border-color: #50dfff !important; /* Bright cyan glow on hover */
    box-shadow: 0 12px 30px rgba(80, 223, 255, 0.4) !important;
    transform: translateY(-6px) !important;
  }

  /* 3. Card Image Styling */
  main.blog_section_stylings figure {
    margin-bottom: 15px !important;
    overflow: hidden !important;
    border-radius: 8px !important;
  }

  main.blog_section_stylings .blog_box_image {
    width: 100% !important;
    height: 200px !important;
    object-fit: cover !important;
    border-radius: 8px !important;
    transition: transform 0.4s ease !important;
  }

  main.blog_section_stylings .post-box.card_bg_div_box:hover .blog_box_image {
    transform: scale(1.05) !important;
  }

  /* 4. Blog Titles Styling */
  main.blog_section_stylings h5.box-title,
  main.blog_section_stylings a.box-title {
    color: #ffffff !important;
    font-size: 18px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    line-height: 1.4 !important;
    transition: color 0.3s ease !important;
  }

  main.blog_section_stylings a.box-title:hover {
    color: #50dfff !important;
  }

  /* 5. Description Text Styling */
  main.blog_section_stylings .post-desc {
    flex-grow: 1 !important;
    color: #d1e8f7 !important; /* Ice blue readable text */
    font-size: 14px !important;
    line-height: 1.6 !important;
  }

  /* 6. Read More Button Styling */
  main.blog_section_stylings .blog_main_btn {
    background: linear-gradient(90deg, #50dfff 0%, #00b0ff 100%) !important;
    color: #043358 !important;
    font-weight: 700 !important;
    border-radius: 25px !important;
    padding: 8px 22px !important;
    border: none !important;
    outline: none !important;
    cursor: pointer !important;
    transition: all 0.3s ease-in-out !important;
    margin-top: 15px !important;
    margin-bottom: 10px !important;
    width: auto !important;
    display: inline-block !important;
  }

  main.blog_section_stylings .blog_main_btn:hover {
    background: #ffffff !important;
    color: #075f9d !important;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.7) !important;
    transform: translateY(-2px) !important;
  }

  /* 7. Date Badge Styling */
  main.blog_section_stylings .blog-date {
    margin-top: 10px !important;
    font-size: 13px !important;
    background: rgba(80, 223, 255, 0.15) !important;
    color: #50dfff !important;
    border: 1px solid rgba(80, 223, 255, 0.4) !important;
    display: inline-block !important;
    padding: 6px 12px !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
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
              $final_url = !empty($row['slug']) ? (preg_match('/^[a-zA-Z0-9_-]+$/D', $row['slug']) ? './' : 'fullblog.php?slug=') . rawurlencode($row['slug']) : 'fullblog.php?id=' . (int) $row['id'];

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

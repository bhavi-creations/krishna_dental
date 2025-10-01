<?php
// Database connection
include './db.connection/db_connection.php';

// =====================
// 1️⃣ Fetch ALL blogs (latest first)
// =====================
$sql = "SELECT id, title, service, main_content, full_content, main_image, video
        FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);

$blogs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}

// Count total blogs
$total_blogs = count($blogs);

// Split equally (left/right sidebar if needed)
$left_count = ceil($total_blogs / 2);
$right_count = $total_blogs - $left_count;

// Function to limit words in title
function get_words($text, $limit)
{
    $words = explode(" ", strip_tags($text));
    return implode(" ", array_slice($words, 0, $limit));
}

// =====================
// 2️⃣ Fetch single blog for service_details.php
// =====================
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$blog = null;
if ($blog_id > 0) {
    $stmt = $conn->prepare("SELECT id, title, service, main_content, full_content, main_image, video,
                                   section1_content, section1_image,
                                   section2_content, section2_image,
                                   section3_content, section3_image
                            FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
}

// Uploads directory
$uploadsDir = "uploads/blogs/";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krishna-Dental</title>

    <!-- libraries CSS -->
    <!-- <link rel="stylesheet" href="assets2/icon/flaticon_digicom.css"> -->
    <link rel="stylesheet" href="assets2/icon/flaticon_err.css">
    <link rel="stylesheet" href="assets2/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets2/vendor/splide/splide.min.css">
    <link rel="stylesheet" href="assets2/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets2/vendor/animate-wow/animate.min.css">
    <link rel="stylesheet" href="assets2/vendor/slim-select/slimselect.css">

    <!-- custom CSS -->
    <link rel="stylesheet" href="assets2/css/style.css">
</head>

<body>
    <div class="preloader" id="preloader">
        <div class="loader"></div>
    </div>

    <!-- SIDEBAR SECTION START -->
    <div class="ul-sidebar">
        <!-- header -->
        <div class="ul-sidebar-header">
            <div class="ul-sidebar-header-logo">
                <a href="index.html">
                    <img src="assets2/img/logo.svg" alt="logo" class="logo">
                </a>
            </div>
            <!-- sidebar closer -->
            <button class="ul-sidebar-closer"><i class="flaticon-close"></i></button>
        </div>

        <div class="ul-sidebar-header-nav-wrapper d-block d-lg-none"></div>

        <!-- sidebar content -->
        <div>
            <div class="ul-sidebar-txt-block">
                <span class="title ul-sidebar-footer-title">About Us</span>
                <p class="descr">Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim odio eveniet ex dicta
                    beatae, repellat voluptatibus ratione alias modi maxime, quaerat sed iste nihil molestiae numquam
                    unde fuga qui velit!</p>
            </div>

            <div class="ul-sidebar-txt-block">
                <span class="title ul-sidebar-footer-title">Location</span>
                <p class="descr"><i class="flaticon-pin"></i><span>123 Main Street, New York, NY 10012</span></p>
            </div>

            <div class="ul-sidebar-txt-block">
                <span class="title ul-sidebar-footer-title">Contact Us</span>
                <p class="descr"><i class="flaticon-call"></i><a href="tel:1234567890">123-456-7890</a></p>
            </div>
        </div>

        <!-- sidebar footer -->
        <div class="ul-sidebar-footer">
            <span class="ul-sidebar-footer-title">Follow us</span>

            <div class="ul-sidebar-footer-social">
                <a href="#"><i class="flaticon-facebook"></i></a>
                <a href="#"><i class="flaticon-twitter"></i></a>
                <a href="#"><i class="flaticon-instagram"></i></a>
                <a href="#"><i class="flaticon-youtube"></i></a>
            </div>
        </div>
    </div>
    <!-- SIDEBAR SECTION END -->


    <!-- SEARCH MODAL SECTION START -->
    <div class="ul-search-form-wrapper flex-grow-1 flex-shrink-0">
        <button class="ul-search-closer"><i class="flaticon-close"></i></button>

        <form action="#" class="ul-search-form">
            <div class="ul-search-form-right">
                <input type="search" name="search" id="ul-search" placeholder="Search Here">
                <button type="submit"><span class="icon"><i class="flaticon-search"></i></span></button>
            </div>
        </form>
    </div>
    <!-- SEARCH MODAL SECTION END -->





    <main>



        <section class="ul-service-details ul-section-spacing full_blogs_section d-none d-lg-block ">
            <div class="container-fluid">
                <div class="row g-xl-5 g-4 mx-3">

                    <!-- Left Sidebar (First Half Blogs) -->
                    <div class="col-lg-3 col-md-5 mt-5">
                        <div class="ul-service-details-sidebar fixed-sidebar">
                            <!-- Show only 2 blogs at a time, scroll for rest -->
                            <div class="ul-service-details-sidebar-widget blog-sidebar-list" style="max-height:200px; overflow-y:auto;">
                                <h4 class="text-center mb-3 latest_blog_section ">Latest Blogs</h4>
                                <?php
                                if (!empty($blogs)) {
                                    foreach (array_slice($blogs, 0, $left_count) as $row) {
                                        $image_path = !empty($row['main_image'])
                                            ? "./admin/uploads/photos/{$row['main_image']}"
                                            : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                                        echo "
              <a href='service_detsils.php?id={$row['id']}' class='blog-item d-flex align-items-center mb-3 text-decoration-none'>
                  <img src='{$image_path}' class='me-2 blog-thumb' alt='Blog Image'>
                  <h6 class='mb-0 blog-title'>" . get_words($row['title'], 6) . "...</h6>
              </a>
              ";
                                    }
                                } else {
                                    echo "<p>No blogs found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>


                    <!-- Middle Content -->
                    <div class="col-lg-6 col-md-6 d-flex justify-content-center align-items-center">
                        <div class="scrollable-content text-center">
                            <?php
                            // Determine the link (if empty, use #)
                            $redirect_link = !empty($blog['logo_link']) ? htmlspecialchars($blog['logo_link']) : '#';
                            ?>

                            <a href="https://krishnadentacureclinic.com/"> <img src="./assets/img/logo/krishna_new_logo.png" alt="" class="img-fluid"></a>


                            <!-- Title -->
                            <?php if (!empty($blog['title'])): ?>
                                <h3 class="ul-service-details-title">
                                    <a href="<?php echo $redirect_link; ?>" target="_blank" style="text-decoration:none; color:inherit;">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                        </div>
                    </div>


                    <!-- Right Sidebar (Second Half Blogs) -->
                    <div class="col-lg-3 col-md-3 mt-5">
                        <div class="ul-service-details-sidebar fixed-sidebar">
                            <div class="ul-service-details-sidebar-widget blog-sidebar-list" style="max-height:200px; overflow-y:auto;">
                                <h4 class="text-center mb-3 latest_blog_section">More Blogs</h4>

                                <?php
                                if (!empty($blogs)) {
                                    foreach (array_slice($blogs, $left_count, $right_count) as $row) {
                                        $image_path = !empty($row['main_image'])
                                            ? "./admin/uploads/photos/{$row['main_image']}"
                                            : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                                        echo "
                        <a href='service_detsils.php?id={$row['id']}' class='more-blog-link blog-item d-flex align-items-center w-100 mb-3 text-decoration-none'>
                            <img src='{$image_path}' class='me-2 blog-thumb' style='width:30px; height:30px; object-fit:cover; border-radius:5px;' alt='Blog Image'>
                            <h6 class='mb-0 blog-title'>" . get_words($row['title'], 6) . "...</h6>
                        </a>
                    ";
                                    }
                                } else {
                                    echo "<p>No blogs found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </section>


        <section class="ul-service-details ul-section-spacing full_blogs_section d-block d-lg-none">
            <div class="container-fluid">
                <div class="row g-xl-5 g-4 mx-3">



                    <!-- Middle Content -->
                    <div class="col-lg-6  d-flex justify-content-center align-items-center ">
                        <div class="scrollable-content text-center blogs_logo">
                            <?php
                            // Determine the link (if empty, use #)
                            $redirect_link = !empty($blog['logo_link']) ? htmlspecialchars($blog['logo_link']) : '#';
                            ?>

                            <!-- Logo -->

                            <a href="https://krishnadentacureclinic.com/"> <img src="./assets/img/logo/krishna_new_logo.png" alt="" class="img-fluid"></a>



                            <!-- <?php if (!empty($blog['logo'])): ?>
                                <a href="<?php echo $redirect_link; ?>" target="_blank">
                                    <img src="./admin/uploads/logos/<?php echo htmlspecialchars($blog['logo']); ?>"
                                        class="img-fluid mb-3"
                                        alt="Blog Logo"
                                        style="max-height:150px; width:auto;">
                                </a>
                            <?php else: ?>
                                <a href="<?php echo $redirect_link; ?>" target="_blank">
                                    <img src="assets/img/default-logo.png"
                                        class="img-fluid mb-3"
                                        alt="Default Logo"
                                        style="max-height:150px; width:auto;">
                                </a>
                            <?php endif; ?> -->

                            <!-- Title -->
                            <?php if (!empty($blog['title'])): ?>
                                <h3 class="ul-service-details-title mt-5 " style="font: size 16px;">
                                    <a href="<?php echo $redirect_link; ?>" target="_blank" style="text-decoration:none; color:inherit;">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                        </div>
                    </div>




                    <!-- Left Sidebar (First Half Blogs) -->
                    <div class="col-lg-3 col-md-6 ">
                        <div class="ul-service-details-sidebar fixed-sidebar">
                            <!-- Show only 2 blogs at a time, scroll for rest -->
                            <div class="ul-service-details-sidebar-widget blog-sidebar-list" style="max-height:200px; overflow-y:auto;">
                                <h4 class="text-center mb-3 latest_blog_section">Latest Blogs</h4>
                                <?php
                                if (!empty($blogs)) {
                                    foreach (array_slice($blogs, 0, $left_count) as $row) {
                                        $image_path = !empty($row['main_image'])
                                            ? "./admin/uploads/photos/{$row['main_image']}"
                                            : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                                        echo "
              <a href='service_detsils.php?id={$row['id']}' class='blog-item d-flex align-items-center mb-3 text-decoration-none'>
                  <img src='{$image_path}' class='me-2 blog-thumb' alt='Blog Image'>
                  <h6 class='mb-0 blog-title'>" . get_words($row['title'], 6) . "...</h6>
              </a>
              ";
                                    }
                                } else {
                                    echo "<p>No blogs found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>



                    <!-- Right Sidebar (Second Half Blogs) -->
                    <div class="col-lg-3 col-md-6">
                        <div class="ul-service-details-sidebar fixed-sidebar">
                            <div class="ul-service-details-sidebar-widget blog-sidebar-list" style="max-height:200px; overflow-y:auto;">
                                <h4 class="text-center mb-3 latest_blog_section">More Blogs</h4>

                                <?php
                                if (!empty($blogs)) {
                                    foreach (array_slice($blogs, $left_count, $right_count) as $row) {
                                        $image_path = !empty($row['main_image'])
                                            ? "./admin/uploads/photos/{$row['main_image']}"
                                            : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                                        echo "
                        <a href='service_detsils.php?id={$row['id']}' class='more-blog-link blog-item d-flex align-items-center w-100 mb-3 text-decoration-none'>
                            <img src='{$image_path}' class='me-2 blog-thumb' style='width:30px; height:30px; object-fit:cover; border-radius:5px;' alt='Blog Image'>
                            <h6 class='mb-0 blog-title'>" . get_words($row['title'], 6) . "...</h6>
                        </a>
                    ";
                                    }
                                } else {
                                    echo "<p>No blogs found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </section>



        <section class="ul-service-details  second_section_blogs  d-none  d-lg-block">
            <div class="container-fluid">
                <div class="row g-xl-5 g-4 mx-3">

                    <div class="col-lg-2 col-md-2 blogs_side_branding">

                        <img src="./assets/img/service_side_image1.png" alt="" class="img-fluid">






                        <div class="card aligner-card" style="display:flex; justify-content:center; align-items:center; flex-direction:column; padding:8px 5px 5px 0px; border-radius:12px; background:linear-gradient(135deg, #f1f5ff, #ffffff); box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center;">

                            <!-- <h3 class="mani" style="color:#333; font-weight:600; margin-bottom:10px;">
                                            Contact Us
                                        </h3> -->

                            <p class="logo-link">
                                Aligners
                            </p>

                            <!-- <p style="font-size:20px; font-weight:600; color:#28a745; margin:8px 0;">
                                            Starting at <span style="color:#e63946;">₹9,999</span>
                                        </p> -->

                            <!-- <strong>
                                            <a href="tel:+919290019948"
                                                style="text-decoration:none; color:#fff; background:#007bff; padding:10px 18px; border-radius:8px; font-size:16px; display:inline-block; margin-top:10px; transition:0.3s;">
                                                📞 Call Now: +91 9290019948
                                            </a>
                                        </strong> -->
                        </div>









                    </div>
                    <div class="col-lg-8 col-md-8 ">
                        <?php if ($blog): ?>
                            <div style="max-height: 380px; overflow-y: auto; padding-right:10px;">
                                <!-- Logo -->
                                <!-- <?php if (!empty($blog['logo'])): ?>
                                                            <div class="text-center mb-3">
                                                                <a href="<?php echo !empty($blog['logo_link']) ? htmlspecialchars($blog['logo_link']) : '#'; ?>" target="_blank">
                                                                    <img src="uploads/logos/<?php echo htmlspecialchars($blog['logo']); ?>"
                                                                        style="max-height:100px; width:auto;" alt="Blog Logo">
                                                                </a>
                                                            </div>
                                                        <?php endif; ?> -->

                                <!-- Service Name -->
                                <?php if (!empty($blog['service'])): ?>
                                    <h2 class="ul-service-details-title text-center" style="font: size 18px;"><?php echo htmlspecialchars($blog['service']); ?></h2>
                                <?php endif; ?>




                                <!-- Main Content -->
                                <?php if (!empty($blog['main_content'])): ?>
                                    <div class="ul-service-details-descr" style="font-size: 18px;">
                                        <?php
                                        // Allow basic formatting tags: p, br, ul, ol, li, b, i, strong, em
                                        echo strip_tags($blog['main_content'], '<p><br><ul><ol><li><b><i><strong><em>');
                                        ?>
                                    </div>
                                <?php endif; ?>





                                <!-- 
                                                        <div class="row mb-3 mx-2">
                                                            <?php if (!empty($blog['main_image'])): ?>
                                                                <div class="col-12 col-md-6 mb-3 mb-md-0 text-center">
                                                                    <img src="./admin/uploads/photos/<?php echo htmlspecialchars($blog['main_image']); ?>"
                                                                        alt="Main Image" class="img-fluid" style="max-height:200px; width:auto;">
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($blog['video'])): ?>
                                                                <div class="col-12 col-md-6 text-center">
                                                                    <video src="./admin/uploads/videos/<?php echo htmlspecialchars($blog['video']); ?>"
                                                                        controls style="max-width:100%; height:auto;"></video>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div> -->


                                <div class="container">
                                    <div class="row mb-3 justify-content-center align-items-stretch">

                                        <?php if (!empty($blog['main_image'])): ?>
                                            <div class="col-12 col-md-6 mb-3 text-center d-flex">
                                                <img src="./admin/uploads/photos/<?php echo htmlspecialchars($blog['main_image']); ?>"
                                                    alt="Main Image"
                                                    class="img-fluid rounded shadow-sm w-100"
                                                    style="max-height: 300px; object-fit: cover;">
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($blog['video'])): ?>
                                            <div class="col-12 col-md-6 mb-3 text-center d-flex">
                                                <video class="rounded shadow-sm w-100"
                                                    src="./admin/uploads/videos/<?php echo htmlspecialchars($blog['video']); ?>"
                                                    controls
                                                    style="max-height: 300px; object-fit: cover;">
                                                </video>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>











                                <!-- Full Content -->
                                <?php if (!empty($blog['full_content'])): ?>
                                    <div class="ul-service-details-full-content mt-3" style="font-size: 18px;">
                                        <?php
                                        // Allow basic formatting tags: p, br, ul, ol, li, b, i, strong, em
                                        echo strip_tags($blog['full_content'], '<p><br><ul><ol><li><b><i><strong><em>');
                                        ?>
                                    </div>
                                <?php endif; ?>



                                <!-- option images   -->

                                <?php if (!empty($blog['section1_content'])): ?>
                                    <div class="section1-content mt-3" style="font-size: 18px;">
                                        <?php echo $blog['section1_content']; // HTML content 
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <!-- <?php if (!empty($blog['section1_image'])): ?>

                                                            <img src="./admin/uploads/photos/<?php echo htmlspecialchars($blog['section1_image']); ?>"
                                                                alt="Section 1 Image"
                                                                class="img-fluid rounded shadow-sm w-100"
                                                                style="max-height: 300px; object-fit: cover;">

                                                        <?php endif; ?> -->

                                <?php
                                $section1_image_path = './admin/uploads/photos/' . $blog['section1_image'];

                                if (!empty($blog['section1_image']) && file_exists($section1_image_path)): ?>
                                    <div style="display:flex; justify-content:center; align-items:center; width:500px; height:500px; margin:auto; overflow:hidden; border:1px solid #ddd; border-radius:8px;">
                                        <img src="<?php echo htmlspecialchars($section1_image_path); ?>"
                                            alt="Section 1 Image"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                <?php endif; ?>






                                <?php if (!empty($blog['section2_content'])): ?>
                                    <div class="section2-content mt-3" style="font-size: 18px;">
                                        <?php echo $blog['section2_content']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $section2_image_path = './admin/uploads/photos/' . $blog['section2_image'];

                                if (!empty($blog['section2_image']) && file_exists($section2_image_path)): ?>
                                    <div style="display:flex; justify-content:center; align-items:center; width:500px; height:500px; margin:auto; overflow:hidden; border:1px solid #ddd; border-radius:8px;">
                                        <img src="<?php echo htmlspecialchars($section2_image_path); ?>"
                                            alt="Section 2 Image"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                <?php endif; ?>



                                <?php if (!empty($blog['section3_content'])): ?>
                                    <div class="section3-content mt-3" style="font-size: 18px;">
                                        <?php echo $blog['section3_content']; ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $section3_image_path = './admin/uploads/photos/' . $blog['section3_image'];

                                if (!empty($blog['section3_image']) && file_exists($section3_image_path)): ?>
                                    <div style="display:flex; justify-content:center; align-items:center; width:500px; height:500px; margin:auto; overflow:hidden; border:1px solid #ddd; border-radius:8px;">
                                        <img src="<?php echo htmlspecialchars($section3_image_path); ?>"
                                            alt="Section 3 Image"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                <?php endif; ?>

















                                <style>
                                    .comment-overlay {
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background: rgba(0, 0, 0, 0.5);
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        z-index: 999;
                                    }

                                    .comment-box {
                                        background: #fff;
                                        padding: 20px;
                                        border-radius: 8px;
                                        width: 90%;
                                        max-width: 500px;
                                        position: relative;
                                    }

                                    .close-btn {
                                        position: absolute;
                                        top: 10px;
                                        right: 15px;
                                        font-size: 24px;
                                        cursor: pointer;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea,
                                    .comment-box button {
                                        width: 100%;
                                        margin-bottom: 10px;
                                        padding: 10px;
                                        border-radius: 5px;
                                        border: 1px solid #ccc;
                                    }

                                    .comment-box button {
                                        background-color: #007bff;
                                        color: #fff;
                                        border: none;
                                        cursor: pointer;
                                    }

                                    .comment-list {
                                        margin-top: 20px;
                                        padding: 10px;
                                        background: #f9f9f9;
                                        border-radius: 8px;
                                    }

                                    .comment-item {
                                        padding: 10px;
                                        border-bottom: 1px solid #ddd;
                                    }

                                    .comment-item:last-child {
                                        border-bottom: none;
                                    }

                                    .comment-item strong {
                                        display: block;
                                        margin-bottom: 5px;
                                    }

                                    .comment-item p {
                                        margin: 0;
                                    }
                                </style>



                                <!-- Styling -->
                                <style>
                                    .show-comment-btn {
                                        background: #007bff;
                                        color: white;
                                        padding: 10px 18px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        margin-bottom: 10px;
                                        transition: background 0.3s ease;
                                    }

                                    .show-comment-btn:hover {
                                        background: #0056b3;
                                    }

                                    .comment-box {
                                        /* background: red; */
                                        background-image: radial-gradient(circle, #e8f2f9, #dcedf9, #d0e8f9, #c4e3f9, #b7def9, #b1dcf9, #abd9f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9) !important;
                                        padding: 20px;
                                        border-radius: 8px;
                                        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
                                        max-width: 600px;
                                    }

                                    .comment-box h3 {
                                        margin-bottom: 15px;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea {
                                        width: 100%;
                                        padding: 10px;
                                        margin-bottom: 12px;
                                        border: 1px solid #ddd;
                                        border-radius: 5px;
                                    }

                                    .comment-box button {
                                        background: #28a745;
                                        color: white;
                                        padding: 10px 16px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        transition: background 0.3s ease;
                                    }

                                    .comment-box button:hover {
                                        background: #1e7e34;
                                    }
                                </style>







                                <?php
                                // Auto DB Connection (localhost / live)
                                $host = 'localhost';
                                if ($_SERVER['SERVER_NAME'] == 'localhost') {
                                    $user = "root";
                                    $pass = "";
                                    $db = "krishnadental";
                                } else {
                                    $user = "krishnadentacureclinic";
                                    $pass = "ip4IvBVvK8TlT7y";
                                    $db = "krishnadentacureclinic";
                                }

                                try {
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // ✅ Blog ID check
                                    $blog_id = isset($blog['id']) ? intval($blog['id']) : 0;

                                    // ✅ Fetch all comments for this blog
                                    $stmt = $pdo->prepare("SELECT user_name, comment 
                                                FROM blog_comments 
                                                WHERE blog_id = :blog_id 
                                                ORDER BY created_at DESC");
                                    $stmt->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $comment_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("❌ DB Error: " . $e->getMessage());
                                }
                                ?>


                                <!-- Write Comment Button -->
                                <button class="show-comment-btn mt-5" onclick="toggleCommentBox()">✍️ Write a Comment</button>

                                <!-- Comment Form Popup -->
                                <div id="comment-overlay" class="comment-overlay" style="display:none;">
                                    <div class="comment-box">
                                        <span class="close-btn" onclick="toggleCommentBox()">&times;</span>
                                        <h3>💬 Leave a Comment</h3>
                                        <form action="save_comment.php" method="POST">
                                            <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">

                                            <input type="text" name="user_name" placeholder="Your Name" required>
                                            <input type="email" name="user_email" placeholder="Your Email" required>
                                            <textarea name="comment" rows="4" placeholder="Write your comment..." required></textarea>

                                            <button type="submit">Post Comment</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Display Comments -->
                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    $comment_id = intval($_POST['comment_id']);
                                    $type = $_POST['type'];

                                    if (!in_array($type, ['like', 'dislike'])) {
                                        echo json_encode(["success" => false, "message" => "Invalid type"]);
                                        exit;
                                    }

                                    if (!isset($_SESSION['reactions'])) {
                                        $_SESSION['reactions'] = [];
                                    }

                                    // Check if user already reacted
                                    if (isset($_SESSION['reactions'][$comment_id])) {
                                        echo json_encode(["success" => false, "message" => "You can only react once."]);
                                        exit;
                                    }

                                    // Update database count
                                    if ($type === 'like') {
                                        $conn->query("UPDATE blog_comments SET likes = likes + 1 WHERE id=$comment_id");
                                    } else {
                                        $conn->query("UPDATE blog_comments SET dislikes = dislikes + 1 WHERE id=$comment_id");
                                    }

                                    // Store reaction in session
                                    $_SESSION['reactions'][$comment_id] = $type;

                                    // Fetch updated counts
                                    $res = $conn->query("SELECT likes, dislikes FROM blog_comments WHERE id=$comment_id");
                                    $row = $res->fetch_assoc();

                                    echo json_encode([
                                        "success" => true,
                                        "likes" => (int)$row['likes'],
                                        "dislikes" => (int)$row['dislikes']
                                    ]);
                                }
                                ?>

                                <div class="comment-list">
                                    <h4>📝 Latest Comments</h4>
                                    <div class="row">
                                        <?php
                                        // All comments fetch
                                        $all_comments_sql = "SELECT * FROM blog_comments WHERE blog_id = '$blog_id' ORDER BY id DESC";
                                        $all_comment_result = $conn->query($all_comments_sql);

                                        if ($all_comment_result && $all_comment_result->num_rows > 0) {
                                            while ($row = $all_comment_result->fetch_assoc()) {
                                                $comment_id = $row['id'];
                                                $user_name  = htmlspecialchars($row['user_name']);
                                                $comment    = htmlspecialchars($row['comment']);
                                                $reply_text = htmlspecialchars($row['reply_text']);
                                                $likes      = (int)$row['likes'];
                                                $dislikes   = (int)$row['dislikes'];

                                                echo "
                                                                <div class='col-md-6 mb-3'>
                                                                    <div class='comment-item p-3 border rounded shadow-sm h-100'>
                                                                        <p><strong>Name:</strong> $user_name</p>
                                                                        <p><strong>Comment:</strong> $comment</p>";

                                                // ✅ If reply exists, show replies count (split by || for multiple replies)
                                                if (!empty($reply_text)) {
                                                    $replies = explode("||", $reply_text); // multiple replies stored as text separated by ||
                                                    $reply_count = count($replies);

                                                    echo "
                                                                        <a href='javascript:void(0)' class='text-primary small' onclick='toggleReply($comment_id)'>
                                                                            $reply_count Reply" . ($reply_count > 1 ? "ies" : "") . "
                                                                        </a>
                                                                        <div id='reply-box-$comment_id' class='mt-2' style='display:none;'>";

                                                    foreach ($replies as $reply) {
                                                        $reply = htmlspecialchars(trim($reply));
                                                        echo "
                                                                            <div class='p-2 mb-1 bg-light border rounded'>
                                                                                <strong>krishnadental Dental Hospital :</strong> $reply
                                                                            </div>";
                                                    }

                                                    echo "</div>";
                                                }

                                                echo "
                                                                        <!-- Like / Dislike buttons -->
                                                                        <div class='mt-2 d-flex justify-content-between'>
                                                                            <button class='btn btn-sm btn-outline-success' onclick='updateReaction($comment_id, \"like\")'>
                                                                                👍 Like (<span id='like-count-$comment_id'>$likes</span>)
                                                                            </button>
                                                                            <button class='btn btn-sm btn-outline-danger' onclick='updateReaction($comment_id, \"dislike\")'>
                                                                                👎 Dislike (<span id='dislike-count-$comment_id'>$dislikes</span>)
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                ";
                                            }
                                        } else {
                                            echo "<div class='col-12'><p>No comments yet. Be the first to comment.!</p></div>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <script>
                                    function toggleReply(commentId) {
                                        let replyBox = document.getElementById("reply-box-" + commentId);
                                        replyBox.style.display = (replyBox.style.display === "none") ? "block" : "none";
                                    }
                                </script>




                                <script>
                                    function updateReaction(commentId, type) {
                                        let xhr = new XMLHttpRequest();
                                        xhr.open("POST", "update_reaction.php", true);
                                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                try {
                                                    let res = JSON.parse(xhr.responseText);
                                                    if (res.success) {
                                                        document.getElementById("like-count-" + commentId).innerText = res.likes;
                                                        document.getElementById("dislike-count-" + commentId).innerText = res.dislikes;
                                                    } else {
                                                        alert("❌ Failed to update reaction");
                                                    }
                                                } catch (e) {
                                                    console.error("Invalid JSON:", xhr.responseText);
                                                }
                                            }
                                        };
                                        xhr.send("comment_id=" + commentId + "&type=" + type);
                                    }
                                </script>






                                <!-- JS -->
                                <script>
                                    function toggleCommentBox() {
                                        var overlay = document.getElementById("comment-overlay");
                                        if (overlay.style.display === "none" || overlay.style.display === "") {
                                            overlay.style.display = "flex"; // show
                                        } else {
                                            overlay.style.display = "none"; // hide
                                        }
                                    }
                                </script>













                            </div>
                            <!-- CSS -->









                        <?php else: ?>
                            <p>No blog details found.</p>
                        <?php endif; ?>











                    </div>


                    <div class="col-lg-2 col-md-2">


                        <img src="./assets/img/service_side_image2.png" alt="" class="img-fluid">


                        <div class="card aligner-card" style="display:flex; justify-content:center; align-items:center; flex-direction:column; padding:8px 5px 5px 0px; border-radius:12px; background:linear-gradient(135deg, #f1f5ff, #ffffff); box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center;">



                            <!-- <a href="https://krishnadentacureclinic.com/"> <img src="./assets/img/logo/krishna_new_logo.png" alt="" class="img-fluid"></a> -->




                            <a href="https://krishnadentacureclinic.com/" class="shake-link">
                                <img src="./assets/img/logo/krishna_new_logo.png"
                                    alt="Vision Dental"
                                    class="img-fluid shake-img">
                            </a>




                            <!-- <p style="font-size:18px; margin:5px 0; color:#444;">
                                            krishnadental
                                        </p>

                                        <p style="font-size:20px; font-weight:600; color:#28a745; margin:8px 0;">
                                            Dental <span style="color:#e63946;">Hospital</span>
                                        </p> -->


                        </div>




                    </div>
                </div>
            </div>
            </div>
        </section>


        <section class="ul-service-details  second_section_blogs  d-block     d-lg-none">
            <div class="container-fluid">
                <d class="row g-xl-5 g-4 mx-3">


                    <div class=" col-md-12 ">
                        <?php if ($blog): ?>
                            <div style="max-height: 380px; overflow-y: auto; padding-right:10px;">
                                <!-- Logo -->
                                <!-- <?php if (!empty($blog['logo'])): ?>
                                    <div class="text-center mb-3">
                                        <a href="<?php echo !empty($blog['logo_link']) ? htmlspecialchars($blog['logo_link']) : '#'; ?>" target="_blank">
                                            <img src="uploads/logos/<?php echo htmlspecialchars($blog['logo']); ?>"
                                                style="max-height:100px; width:auto;" alt="Blog Logo">
                                        </a>
                                    </div>
                                <?php endif; ?> -->

                                <!-- Service Name -->
                                <?php if (!empty($blog['service'])): ?>
                                    <h2 class="ul-service-details-title text-center"><?php echo htmlspecialchars($blog['service']); ?></h2>
                                <?php endif; ?>




                                <!-- Main Content -->
                                <?php if (!empty($blog['main_content'])): ?>
                                    <div class="ul-service-details-descr" style="font-size: 18px;">
                                        <?php
                                        // Allow basic formatting tags: p, br, ul, ol, li, b, i, strong, em
                                        echo strip_tags($blog['main_content'], '<p><br><ul><ol><li><b><i><strong><em>');
                                        ?>
                                    </div>
                                <?php endif; ?>





                                <!-- 
                                <div class="row mb-3 mx-2">
                                    <?php if (!empty($blog['main_image'])): ?>
                                        <div class="col-12 col-md-6 mb-3 mb-md-0 text-center">
                                            <img src="./admin/uploads/photos/<?php echo htmlspecialchars($blog['main_image']); ?>"
                                                alt="Main Image" class="img-fluid" style="max-height:200px; width:auto;">
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($blog['video'])): ?>
                                        <div class="col-12 col-md-6 text-center">
                                            <video src="./admin/uploads/videos/<?php echo htmlspecialchars($blog['video']); ?>"
                                                controls style="max-width:100%; height:auto;"></video>
                                        </div>
                                    <?php endif; ?>
                                </div> -->

                                <div class="container">
                                    <div class="row mb-3 justify-content-center align-items-stretch">

                                        <?php if (!empty($blog['main_image'])): ?>
                                            <div class="col-12 col-md-6 mb-3 text-center d-flex">
                                                <img src="./admin/uploads/photos/<?php echo htmlspecialchars($blog['main_image']); ?>"
                                                    alt="Main Image"
                                                    class="img-fluid rounded shadow-sm w-100"
                                                    style="max-height: 300px; object-fit: cover;">
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($blog['video'])): ?>
                                            <div class="col-12 col-md-6 mb-3 text-center d-flex">
                                                <video class="rounded shadow-sm w-100"
                                                    src="./admin/uploads/videos/<?php echo htmlspecialchars($blog['video']); ?>"
                                                    controls
                                                    style="max-height: 300px; object-fit: cover;">
                                                </video>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>











                                <!-- Full Content -->
                                <?php if (!empty($blog['full_content'])): ?>
                                    <div class="ul-service-details-full-content mt-3" style="font-size: 18px;">
                                        <?php
                                        // Allow basic formatting tags: p, br, ul, ol, li, b, i, strong, em
                                        echo strip_tags($blog['full_content'], '<p><br><ul><ol><li><b><i><strong><em>');
                                        ?>
                                    </div>
                                <?php endif; ?>













                                <style>
                                    .comment-overlay {
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background: rgba(0, 0, 0, 0.5);
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        z-index: 999;
                                    }

                                    .comment-box {
                                        background: #fff;
                                        padding: 20px;
                                        border-radius: 8px;
                                        width: 90%;
                                        max-width: 500px;
                                        position: relative;
                                    }

                                    .close-btn {
                                        position: absolute;
                                        top: 10px;
                                        right: 15px;
                                        font-size: 24px;
                                        cursor: pointer;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea,
                                    .comment-box button {
                                        width: 100%;
                                        margin-bottom: 10px;
                                        padding: 10px;
                                        border-radius: 5px;
                                        border: 1px solid #ccc;
                                    }

                                    .comment-box button {
                                        background-color: #007bff;
                                        color: #fff;
                                        border: none;
                                        cursor: pointer;
                                    }

                                    .comment-list {
                                        margin-top: 20px;
                                        padding: 10px;
                                        background: #f9f9f9;
                                        border-radius: 8px;
                                    }

                                    .comment-item {
                                        padding: 10px;
                                        border-bottom: 1px solid #ddd;
                                    }

                                    .comment-item:last-child {
                                        border-bottom: none;
                                    }

                                    .comment-item strong {
                                        display: block;
                                        margin-bottom: 5px;
                                    }

                                    .comment-item p {
                                        margin: 0;
                                    }
                                </style>



                                <!-- Styling -->
                                <style>
                                    .show-comment-btn {
                                        background: #007bff;
                                        color: white;
                                        padding: 10px 18px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        margin-bottom: 10px;
                                        transition: background 0.3s ease;
                                    }

                                    .show-comment-btn:hover {
                                        background: #0056b3;
                                    }

                                    .comment-box {
                                        /* background: red; */
                                        background-image: radial-gradient(circle, #e8f2f9, #dcedf9, #d0e8f9, #c4e3f9, #b7def9, #b1dcf9, #abd9f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9) !important;
                                        padding: 20px;
                                        border-radius: 8px;
                                        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
                                        max-width: 600px;
                                    }

                                    .comment-box h3 {
                                        margin-bottom: 15px;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea {
                                        width: 100%;
                                        padding: 10px;
                                        margin-bottom: 12px;
                                        border: 1px solid #ddd;
                                        border-radius: 5px;
                                    }

                                    .comment-box button {
                                        background: #28a745;
                                        color: white;
                                        padding: 10px 16px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        transition: background 0.3s ease;
                                    }

                                    .comment-box button:hover {
                                        background: #1e7e34;
                                    }
                                </style>







                                <?php
                                // Auto DB Connection (localhost / live)
                                $host = 'localhost';
                                if ($_SERVER['SERVER_NAME'] == 'localhost') {
                                    $user = "root";
                                    $pass = "";
                                    $db = "krishnadental";
                                } else {
                                    $user = "krishnadentacureclinic";
                                    $pass = "ip4IvBVvK8TlT7y";
                                    $db = "krishnadentacureclinic";
                                }

                                try {
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // ✅ Blog ID check
                                    $blog_id = isset($blog['id']) ? intval($blog['id']) : 0;

                                    // ✅ Fetch all comments for this blog
                                    $stmt = $pdo->prepare("SELECT user_name, comment 
                           FROM blog_comments 
                           WHERE blog_id = :blog_id 
                           ORDER BY created_at DESC");
                                    $stmt->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $comment_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("❌ DB Error: " . $e->getMessage());
                                }
                                ?>


                                <!-- Write Comment Button -->
                                <button class="show-comment-btn mt-5" onclick="toggleCommentBox()">✍️ Write a Comment</button>

                                <!-- Comment Form Popup -->
                                <div id="comment-overlay" class="comment-overlay" style="display:none;">
                                    <div class="comment-box">
                                        <span class="close-btn" onclick="toggleCommentBox()">&times;</span>
                                        <h3>💬 Leave a Comment</h3>
                                        <form action="save_comment.php" method="POST">
                                            <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">

                                            <input type="text" name="user_name" placeholder="Your Name" required>
                                            <input type="email" name="user_email" placeholder="Your Email" required>
                                            <textarea name="comment" rows="4" placeholder="Write your comment..." required></textarea>

                                            <button type="submit">Post Comment</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Display Comments -->
                                <?php


                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    $comment_id = intval($_POST['comment_id']);
                                    $type = $_POST['type'];

                                    if (!in_array($type, ['like', 'dislike'])) {
                                        echo json_encode(["success" => false, "message" => "Invalid type"]);
                                        exit;
                                    }

                                    if (!isset($_SESSION['reactions'])) {
                                        $_SESSION['reactions'] = [];
                                    }

                                    // Check if user already reacted
                                    if (isset($_SESSION['reactions'][$comment_id])) {
                                        echo json_encode(["success" => false, "message" => "You can only react once."]);
                                        exit;
                                    }

                                    // Update database count
                                    if ($type === 'like') {
                                        $conn->query("UPDATE blog_comments SET likes = likes + 1 WHERE id=$comment_id");
                                    } else {
                                        $conn->query("UPDATE blog_comments SET dislikes = dislikes + 1 WHERE id=$comment_id");
                                    }

                                    // Store reaction in session
                                    $_SESSION['reactions'][$comment_id] = $type;

                                    // Fetch updated counts
                                    $res = $conn->query("SELECT likes, dislikes FROM blog_comments WHERE id=$comment_id");
                                    $row = $res->fetch_assoc();

                                    echo json_encode([
                                        "success" => true,
                                        "likes" => (int)$row['likes'],
                                        "dislikes" => (int)$row['dislikes']
                                    ]);
                                }
                                ?>




                                <div class="comment-list">
                                    <h4>📝 Latest Comments</h4>
                                    <div class="row">
                                        <?php
                                        // All comments fetch
                                        $all_comments_sql = "SELECT * FROM blog_comments WHERE blog_id = '$blog_id' ORDER BY id DESC";
                                        $all_comment_result = $conn->query($all_comments_sql);

                                        if ($all_comment_result && $all_comment_result->num_rows > 0) {
                                            while ($row = $all_comment_result->fetch_assoc()) {
                                                $comment_id = $row['id'];
                                                $user_name  = htmlspecialchars($row['user_name']);
                                                $comment    = htmlspecialchars($row['comment']);
                                                $reply_text = htmlspecialchars($row['reply_text']);
                                                $likes      = (int)$row['likes'];
                                                $dislikes   = (int)$row['dislikes'];

                                                echo "
                                        <div class='col-md-6 mb-3'>
                                            <div class='comment-item p-3 border rounded shadow-sm h-100'>
                                                <p><strong>Name:</strong> $user_name</p>
                                                <p><strong>Comment:</strong> $comment</p>";

                                                // ✅ If reply exists, show replies count (split by || for multiple replies)
                                                if (!empty($reply_text)) {
                                                    $replies = explode("||", $reply_text); // multiple replies stored as text separated by ||
                                                    $reply_count = count($replies);

                                                    echo "
                                                <a href='javascript:void(0)' class='text-primary small' onclick='toggleReply($comment_id)'>
                                                    $reply_count Reply" . ($reply_count > 1 ? "ies" : "") . "
                                                </a>
                                                <div id='reply-box-$comment_id' class='mt-2' style='display:none;'>";

                                                    foreach ($replies as $reply) {
                                                        $reply = htmlspecialchars(trim($reply));
                                                        echo "
                                                    <div class='p-2 mb-1 bg-light border rounded'>
                                                        <strong>krishnadental Dental Hospital :</strong> $reply
                                                    </div>";
                                                    }

                                                    echo "</div>";
                                                }

                                                echo "
                                                <!-- Like / Dislike buttons -->
                                                <div class='mt-2 d-flex justify-content-between'>
                                                    <button class='btn btn-sm btn-outline-success' onclick='updateReaction($comment_id, \"like\")'>
                                                        👍 Like (<span id='like-count-$comment_id'>$likes</span>)
                                                    </button>
                                                    <button class='btn btn-sm btn-outline-danger' onclick='updateReaction($comment_id, \"dislike\")'>
                                                        👎 Dislike (<span id='dislike-count-$comment_id'>$dislikes</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                                            }
                                        } else {
                                            echo "<div class='col-12'><p>No comments yet. Be the first to comment.!</p></div>";
                                        }
                                        ?>
                                    </div>
                                </div>


                                <script>
                                    function updateReaction(commentId, type) {
                                        let xhr = new XMLHttpRequest();
                                        xhr.open("POST", "update_reaction.php", true);
                                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                try {
                                                    let res = JSON.parse(xhr.responseText);
                                                    if (res.success) {
                                                        document.getElementById("like-count-" + commentId).innerText = res.likes;
                                                        document.getElementById("dislike-count-" + commentId).innerText = res.dislikes;
                                                    } else {
                                                        alert("❌ Failed to update reaction");
                                                    }
                                                } catch (e) {
                                                    console.error("Invalid JSON:", xhr.responseText);
                                                }
                                            }
                                        };
                                        xhr.send("comment_id=" + commentId + "&type=" + type);
                                    }
                                </script>





                                <!-- JS -->
                                <script>
                                    function toggleCommentBox() {
                                        var overlay = document.getElementById("comment-overlay");
                                        if (overlay.style.display === "none" || overlay.style.display === "") {
                                            overlay.style.display = "flex"; // show
                                        } else {
                                            overlay.style.display = "none"; // hide
                                        }
                                    }
                                </script>













                            </div>
                            <!-- CSS -->









                        <?php else: ?>
                            <p>No blog details found.</p>
                        <?php endif; ?>











                    </div>

                    <div class=" col-md-6 blogs_side_branding ">

                        <img src="./assets/img/service_side_image1.png" alt="" class="img-fluid">






                        <div class="card aligner-card" style="display:flex; justify-content:center; align-items:center; flex-direction:column; padding:8px 5px 5px 0px; border-radius:12px; background:linear-gradient(135deg, #f1f5ff, #ffffff); box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center;">

                            <!-- <h3 class="mani" style="color:#333; font-weight:600; margin-bottom:10px;">
                                Contact Us
                            </h3> -->

                            <p class="logo-link">
                                Aligners
                            </p>

                            <!-- <p style="font-size:20px; font-weight:600; color:#28a745; margin:8px 0;">
                                Starting at <span style="color:#e63946;">₹9,999</span>
                            </p> -->

                            <!-- <strong>
                                <a href="tel:+919290019948"
                                    style="text-decoration:none; color:#fff; background:#007bff; padding:10px 18px; border-radius:8px; font-size:16px; display:inline-block; margin-top:10px; transition:0.3s;">
                                    📞 Call Now: +91 9290019948
                                </a>
                            </strong> -->
                        </div>









                    </div>
                    <div class=" col-md-6  blogs_side_branding">


                        <img src="./assets/img/service_side_image2.png" alt="" class="img-fluid">


                        <div class="card aligner-card" style="display:flex; justify-content:center; align-items:center; flex-direction:column; padding:8px 5px 5px 0px; border-radius:12px; background:linear-gradient(135deg, #f1f5ff, #ffffff); box-shadow:0 4px 12px rgba(0,0,0,0.1); text-align:center;">



                            <!-- <a href="https://krishnadentacureclinic.com/"> <img src="./assets/img/logo/krishna_new_logo.png" alt="" class="img-fluid"></a> -->

                            <a href="https://krishnadentacureclinic.com/" class="shake-link">
                                <img src="./assets/img/logo/krishna_new_logo.png"
                                    alt="Vision Dental"
                                    class="img-fluid shake-img">
                            </a>




                            <!-- <p style="font-size:18px; margin:5px 0; color:#444;">
                                krishnadental
                            </p>

                            <p style="font-size:20px; font-weight:600; color:#28a745; margin:8px 0;">
                                Dental <span style="color:#e63946;">Hospital</span>
                            </p> -->


                        </div>




                    </div>
            </div>
            </div>
            </div>
        </section>

    </main>



    <!-- libraries JS -->
    <script src="assets2/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets2/vendor/splide/splide.min.js"></script>
    <script src="assets2/vendor/splide/splide-extension-auto-scroll.min.js"></script>
    <script src="assets2/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets2/vendor/animate-wow/wow.min.js"></script>
    <script src="assets2/vendor/fslightbox/fslightbox.js"></script>
    <script src="assets2/vendor/slim-select/slimselect.min.js"></script>


    <!-- custom JS -->
    <script src="assets2/js/main.js"></script>
    <script src="assets2/js/tab.js"></script>
    <script src="assets2/js/accordion.js"></script>
    <script src="assets2/js/progressbar.js"></script>

</body>
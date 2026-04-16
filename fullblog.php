<?php
// 1. Database Connection - Include path ni check cheskondi
include './db.connection/db_connection.php';

// Identifier capture
$blog_input = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($blog_input)) {
    echo "<h1 style='color:gold; text-align:center; margin-top:50px;'>Invalid Blog Request</h1>";
    exit;
}

// 2. Fetch Blog Data
$stmt = $conn->prepare("
    SELECT 
        id, title, slug, main_content, full_content, 
        title_image, main_image, video, 
        telugu_title, telugu_main_content, telugu_full_content,
        section1_image, service, hashtags, keypoints
    FROM blogs 
    WHERE id = ? OR slug = ?
");

$stmt->bind_param("ss", $blog_input, $blog_input);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    echo "<h1 style='color:gold; text-align:center; margin-top:50px;'>Blog Not Found!</h1>";
    exit;
}

// Data mapping
$blog_id = $blog['id'];
$title = $blog['title'];
$main_content = $blog['main_content'];
$full_content = $blog['full_content'];
$main_image = $blog['main_image'];
$video = $blog['video'];
$telugu_title = $blog['telugu_title'];
$telugu_main_content = $blog['telugu_main_content'];
$telugu_full_content = $blog['telugu_full_content'];
$section1_image = $blog['section1_image'];
$service = $blog['service'];

$stmt->close();

// 3. Fetch Likes/Dislikes
$likes_count = 0;
$dislikes_count = 0;
$count_stmt = $conn->prepare("SELECT reaction, COUNT(*) as total FROM blog_reactions WHERE blog_id = ? GROUP BY reaction");
$count_stmt->bind_param("i", $blog_id);
$count_stmt->execute();
$res = $count_stmt->get_result();
while ($row = $res->fetch_assoc()) {
    if ($row['reaction'] == 'like') $likes_count = $row['total'];
    if ($row['reaction'] == 'dislike') $dislikes_count = $row['total'];
}
$count_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* body {
            background-color: black;
            color: white;
        } */

        .fullblogs_section {
            /* background-color: black; */
            /* padding-bottom: 50px; */
        }

        .blog-title,
        .main-content,
        .main-content *,
        .full-content,
        .full-content * {
            color: #000000 !important;
        }



        .badge_service_name {
            background-color: gold;
            color: black;
            font-weight: bold;
            border-radius: 5px;
        }

        .lang-btn.active {
            background: gold !important;
            color: black !important;
            border: 2px solid gold !important;
        }

        .custom-card {
            background: #111;
            border: 1px solid #333;
            transition: 0.3s;
        }

        .custom-card:hover {
            border-color: gold;
        }

        .blog-card-text {
            color: gold;
            text-decoration: none;
            font-size: 14px;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }




        /* card images for slider   */
        .custom-card {
            background: #111;
            border: 1px solid #333;
            transition: 0.3s;
            height: 100%;
            /* Card height align avvadaniki */
            display: flex;
            flex-direction: column;
        }

        .custom-card img {
            width: 100%;
            height: 200px;
            /* Fixed height for consistency */
            object-fit: contain;
            /* Image cut avvakunda full ga chupisthundhi */
            background-color: #000;
            /* Gap unte black cover chesthundhi */
            border-radius: 10px;
        }

        .blog-card-text {
            color: gold;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
        }

        

        .blogs_color {
            color: black !important;
           
        }

        .fullblogs_section_1 {
            background-color: white !important;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="fullblogs_section">
        <div class="container py-5">
            <div class="d-flex justify-content-center mb-4">
                <button id="english-btn" class="lang-btn active btn btn-outline-warning">English</button>
                <button id="telugu-btn" class="lang-btn ms-3 btn btn-outline-warning">తెలుగు</button>
            </div>

            <?php if (!empty($service)): ?>
                <div class="text-center mb-4">
                    <span class="badge_service_name px-4 py-2"><?= htmlspecialchars($service) ?></span>
                </div>
            <?php endif; ?>

            <div class="text-center mb-5">
                <?php if (!empty($video)): ?>
                    <video controls class="w-100 shadow" style="max-width:800px; border-radius:15px;">
                        <source src="./admin/uploads/videos/<?= $video ?>" type="video/mp4">
                    </video>
                <?php elseif (!empty($main_image)): ?>
                    <img src="./admin/uploads/photos/<?= $main_image ?>" class="shadow" style="max-height:500px; width:auto;">
                <?php endif; ?>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="blog-title text-center mb-4" style="font-weight:800;">
                        <span id="title-en"><?= $title ?></span>
                        <span id="title-te" style="display:none;"><?= $telugu_TeTitle = !empty($telugu_title) ? $telugu_title : $title ?></span>
                    </h1>

                    <div class="main-content fs-5">
                        <div id="main-en"><?= $main_content ?></div>
                        <div id="main-te" style="display:none;"><?= $telugu_main_content ?></div>
                    </div>

                    <div class="full-content mt-4">
                        <div id="full-en"><?= $full_content ?></div>
                        <div id="full-te" style="display:none;"><?= $telugu_full_content ?></div>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <button id="like-btn" class="btn btn-outline-success me-3">👍 Like (<span id="like-count"><?= $likes_count ?></span>)</button>
                        <button id="dislike-btn" class="btn btn-outline-danger">👎 Dislike (<span id="dislike-count"><?= $dislikes_count ?></span>)</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- <section class="py-5" style="border-top: 1px solid #222;">
        <div class="container">
            <h2 class="text-center mb-5" style="color:gold;">LATEST BLOGS</h2>
            <div class="swiper blog-swiper">
                <div class="swiper-wrapper">
                    <?php
                    // Refresh connection if needed, but better to use the existing one
                    $latest_sql = "SELECT id, title, slug, main_image FROM blogs ORDER BY created_at DESC LIMIT 10";
                    $latest_res = $conn->query($latest_sql);
                    while ($row = $latest_res->fetch_assoc()):
                        $img = !empty($row['main_image']) ? "./admin/uploads/photos/" . $row['main_image'] : "placeholder.jpg";
                        $link = "fullblog.php?id=" . $row['slug']; // Slug base link for better SEO
                    ?>
                        <div class="swiper-slide">
                            <div class="custom-card p-3 rounded text-center">
                                <img src="<?= $img ?>" style="height:200px; width:100%; object-fit:cover;" class="mb-3">
                                <a href="<?= $link ?>" class="blog-card-text d-block text-truncate"><?= $row['title'] ?></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section> -->




    <div class="fullblogs_section_1">
        <div class="container ">
            <div class="blogs_side ">
                <div class="side-bar">
                    <h1 class="d-flex justify-content-center py-5 blogs_color">LATEST BLOGS</h1>
                    <div class="swiper blog-swiper">
                        <div class="swiper-wrapper">
                            <?php
                            // DB connection
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT id, title, main_image FROM blogs ORDER BY created_at DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $sidebar_image_path = !empty($row['main_image']) ? "./admin/uploads/photos/{$row['main_image']}" : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                                    $title_short = strlen($row['title']) > 50 ? substr($row['title'], 0, 50) . '...' : $row['title'];

                                    echo "
                            <div class='swiper-slide d-flex justify-content-center'>
                                <div class='custom-card background_sidebar text-center' 
                                    style='width:100%; max-width:400px; height:350px; display:flex; flex-direction:column; justify-content:flex-start; align-items:center; padding:10px; border-radius:8px; box-shadow:0px 2px 10px rgba(0,0,0,0.1);'>
                                    <div style='flex:1; display:flex; align-items:center; justify-content:center; width:100%; overflow:hidden;'>
                                        <img src='{$sidebar_image_path}' class='img-fluid' style='width:100%; height:100%; object-fit:cover;' alt='Blog Image'>
                                    </div>
                                    <a href='fullblog.php?id={$row['id']}'>
                                        <p class='blog-card-text mt-2'>{$title_short}</p>
                                    </a>
                                </div>
                            </div>";
                                }
                            } else {
                                echo "<p>No blog posts found.</p>";
                            }
                            $conn->close();
                            ?>
                        </div>

                        <!-- Navigation -->
                        <!-- <div class="swiper-button-next blog-swiper-button-next"></div>
                    <div class="swiper-button-prev blog-swiper-button-prev"></div> -->

                        <!-- Pagination -->
                        <!-- <div class="swiper-pagination blog-swiper-pagination"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Language Logic
        const enBtn = document.getElementById("english-btn");
        const teBtn = document.getElementById("telugu-btn");

        function switchLang(lang) {
            const isEn = (lang === 'en');
            document.getElementById("title-en").style.display = isEn ? "inline" : "none";
            document.getElementById("main-en").style.display = isEn ? "block" : "none";
            document.getElementById("full-en").style.display = isEn ? "block" : "none";

            document.getElementById("title-te").style.display = isEn ? "none" : "inline";
            document.getElementById("main-te").style.display = isEn ? "none" : "block";
            document.getElementById("full-te").style.display = isEn ? "none" : "block";

            enBtn.classList.toggle('active', isEn);
            teBtn.classList.toggle('active', !isEn);
        }

        enBtn.onclick = () => switchLang('en');
        teBtn.onclick = () => switchLang('te');

        // Voting system
        const blogId = <?= json_encode($blog_id) ?>;
        const likeBtn = document.getElementById("like-btn");
        const dislikeBtn = document.getElementById("dislike-btn");

        if (localStorage.getItem("voted_" + blogId)) {
            likeBtn.disabled = dislikeBtn.disabled = true;
        }

        async function castVote(type) {
            const res = await fetch("update_vote.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `blog_id=${blogId}&vote_type=${type}`
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById("like-count").innerText = data.new_likes;
                document.getElementById("dislike-count").innerText = data.new_dislikes;
                localStorage.setItem("voted_" + blogId, true);
                likeBtn.disabled = dislikeBtn.disabled = true;
            }
        }

        likeBtn.onclick = () => castVote('like');
        dislikeBtn.onclick = () => castVote('dislike');

        // Swiper
        new Swiper(".blog-swiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            breakpoints: {
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    </script>
</body>

</html>


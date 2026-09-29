<?php
// 1. Database Connection - Include path ni check cheskondi
include './db.connection/db_connection.php';

// Identifier capture
$blog_input = $_GET['slug'] ?? $_GET['id'] ?? '';

if (!is_string($blog_input) || $blog_input === '') {
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
    WHERE slug = ?
    LIMIT 1
");

$stmt->bind_param("s", $blog_input);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

// Keep old ID links working without treating numeric slug prefixes as IDs.
if (!$blog && !isset($_GET['slug']) && ctype_digit($blog_input)) {
    $stmt->close();
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ? LIMIT 1");
    $stmt->bind_param("s", $blog_input);
    $stmt->execute();
    $blog = $stmt->get_result()->fetch_assoc();
}

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
        .fullblogs_section {
            padding-bottom: 30px;
            background: #e8f4f9;
        }

        .fullblog_hero {
            position: relative;
            overflow: hidden;
            padding: 30px 0 76px;
            color: #fff;
            background:
                radial-gradient(circle at 86% 18%, rgba(45, 207, 245, .22), transparent 28%),
                radial-gradient(circle at 10% 95%, rgba(0, 172, 231, .18), transparent 34%),
                linear-gradient(112deg, #003f70 0%, #075f9d 54%, #034574 100%);
        }

        .fullblog_hero::after {
            position: absolute;
            right: -110px;
            bottom: -210px;
            width: 440px;
            height: 440px;
            border: 1px solid rgba(83, 221, 249, .22);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }

        .fullblog_hero .container,
        .fullblog_article_container,
        .fullblog_related .container {
            position: relative;
            z-index: 1;
            max-width: 1180px;
        }

        .fullblog_utility_row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 38px;
        }

        .fullblog_back_link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .9);
            font-size: 14px;
            font-weight: 600;
        }

        .fullblog_back_link:hover {
            color: #64e5fa;
        }

        .fullblog_lang_switch {
            display: inline-flex;
            gap: 4px;
            padding: 4px;
            border: 1px solid rgba(158, 231, 249, .45);
            border-radius: 8px;
            background: rgba(0, 38, 71, .3);
        }

        .fullblog_lang_switch .lang-btn {
            min-height: 40px;
            padding: 8px 16px;
            border: 0;
            border-radius: 5px;
            color: rgba(255, 255, 255, .86);
            background: transparent;
            font-size: 14px;
            font-weight: 600;
        }

        .fullblog_lang_switch .lang-btn.active {
            color: #003f70;
            background: #59def3;
        }

        .badge_service_name {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            margin-bottom: 18px;
            padding: 6px 13px;
            border: 1px solid rgba(95, 228, 248, .5);
            border-radius: 4px;
            color: #8cecff;
            background: rgba(0, 29, 57, .28);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .fullblog_hero .blog-title {
            max-width: 900px;
            margin: 0 auto;
            color: #fff;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 44px;
            font-weight: 700;
            line-height: 1.16;
            text-align: center;
            overflow-wrap: anywhere;
        }

        .fullblog_article_container {
            margin-top: -42px;
        }

        .fullblog_media {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 180px;
            max-height: 560px;
            overflow: hidden;
            border: 5px solid #fff;
            border-radius: 10px;
            background: #062f52;
            box-shadow: 0 16px 40px rgba(0, 43, 77, .2);
        }

        .fullblog_media img,
        .fullblog_media video {
            display: block;
            width: 100%;
            max-height: 550px;
            object-fit: contain;
        }

        .fullblog_article {
            margin-top: 28px;
            padding: 36px 42px;
            border: 1px solid #cfe5ef;
            border-radius: 8px;
            /* background: #f1f8fb; */
            background: radial-gradient(circle at 86% 18%, rgba(45, 207, 245, .22), transparent 28%), radial-gradient(circle at 10% 95%, rgba(0, 172, 231, .18), transparent 34%), linear-gradient(112deg, #003f70 0%, #075f9d 54%, #034574 100%);
            box-shadow: 0 12px 34px rgba(0, 57, 99, .06);
        }

        .fullblog_article .main-content,
        .fullblog_article .full-content,
        .fullblog_article .main-content *,
        .fullblog_article .full-content * {
            color: #fcfdfd !important;
            /* color: #183a54 !important; */
            font-family: 'Montserrat', Arial, sans-serif;
            line-height: 1.85;
            overflow-wrap: anywhere;
        }

        .fullblog_article .main-content {
            padding-bottom: 24px;
            border-bottom: 1px solid #e0edf3;
            font-size: 17px;
        }

        .fullblog_article .full-content {
            margin-top: 24px !important;
            font-size: 16px;
        }

        .fullblog_article .full-content h2,
        .fullblog_article .full-content h3,
        .fullblog_article .main-content h2,
        .fullblog_article .main-content h3 {
            margin-top: 1.6em;
            color: #075f9d !important;
            font-weight: 700;
            line-height: 1.35;
        }

        .fullblog_article img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }

        .fullblog_reactions {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 34px;
            padding-top: 24px;
            border-top: 1px solid #e0edf3;
        }

        .fullblog_reactions .btn {
            min-height: 44px;
            padding: 10px 17px;
            border: 1px solid #0a8fbd;
            border-radius: 5px;
            color: #087eaa;
            background: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .fullblog_reactions .btn:hover:not(:disabled) {
            color: #fff;
            background: #078dbb;
        }

        .fullblog_reactions .btn:disabled {
            opacity: .55;
        }

        .fullblog_related {
            padding: 62px 0 68px;
            overflow: hidden;
            color: #fff;
            background:
                radial-gradient(circle at 85% 0%, rgba(0, 186, 244, .18), transparent 30%),
                linear-gradient(105deg, #003f70 0%, #075f9d 52%, #034574 100%);
        }

        .fullblog_related_heading {
            margin-bottom: 28px;
            text-align: center;
        }

        .fullblog_related_heading span {
            color: #73e8f7;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .fullblog_related_heading h2 {
            margin: 8px 0 0;
            color: #fff;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 38px;
            font-weight: 700;
        }

        .fullblog_related_heading p {
            margin: 9px 0 0;
            color: rgba(255, 255, 255, .7);
        }

        .fullblog_related .swiper {
            overflow: hidden;
            padding: 4px 4px 15px;
        }

        .fullblog_related_card {
            height: 100%;
            overflow: hidden;
            border: 1px solid rgba(112, 226, 246, .45);
            border-radius: 7px;
            background: rgba(0, 43, 78, .72);
            box-shadow: 0 10px 24px rgba(0, 23, 50, .18);
        }

        .fullblog_related_image {
            display: block;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #062f52;
        }

        .fullblog_related_image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .fullblog_related_card:hover .fullblog_related_image img {
            transform: scale(1.04);
        }

        .fullblog_related_title {
            display: block;
            min-height: 74px;
            padding: 16px;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.5;
        }

        .fullblog_related_title:hover {
            color: #73e8f7;
        }

        .fullblog_related_controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 22px;
        }

        .fullblog_related_controls button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border: 1px solid rgba(111, 229, 248, .75);
            border-radius: 50%;
            color: #fff;
            background: #078dbb;
            cursor: pointer;
        }

        .fullblog_related_controls button:hover {
            background: #08b8dd;
        }

        .fullblog_related_controls .swiper-pagination {
            position: static;
            width: auto;
        }

        .fullblog_related_controls .swiper-pagination-bullet {
            background: #9eefff;
        }

        @media (max-width: 767.98px) {
            .fullblog_hero {
                padding: 22px 0 68px;
            }

            .fullblog_utility_row {
                align-items: flex-start;
                margin-bottom: 28px;
            }

            .fullblog_back_link {
                min-height: 44px;
                padding-top: 10px;
            }

            .fullblog_lang_switch .lang-btn {
                padding: 8px 11px;
            }

            .fullblog_hero .blog-title {
                font-size: 32px;
            }

            .fullblog_article_container {
                margin-top: -34px;
            }

            .fullblog_media {
                min-height: 140px;
                border-width: 3px;
            }

            .fullblog_article {
                margin-top: 18px;
                padding: 23px 18px;
            }

            .fullblog_article .main-content {
                font-size: 16px;
            }

            .fullblog_article .full-content {
                font-size: 15px;
            }

            .fullblog_reactions {
                flex-wrap: wrap;
            }

            .fullblog_related {
                padding: 38px 0 22px;
            }

            .fullblog_related .swiper {
                padding-bottom: 6px;
            }

            .fullblog_related_controls {
                margin-top: 12px;
            }

            .fullblog_related_heading h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 420px) {
            .fullblog_utility_row {
                gap: 8px;
            }

            .fullblog_back_link {
                gap: 6px;
                font-size: 12px;
            }

            .fullblog_lang_switch .lang-btn {
                padding: 7px 9px;
                font-size: 12px;
            }

            .fullblog_hero .blog-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="fullblogs_section">
        <section class="fullblog_hero">
            <div class="container">
                <div class="fullblog_utility_row">
                    <a class="fullblog_back_link" href="blogs.php"><i class="fas fa-arrow-left" aria-hidden="true"></i><span>All Blogs</span></a>
                    <div class="fullblog_lang_switch" role="group" aria-label="Article language">
                        <button id="english-btn" class="lang-btn active" type="button">English</button>
                        <button id="telugu-btn" class="lang-btn" type="button">తెలుగు</button>
                    </div>
                </div>

                <?php if (!empty($service)): ?>
                    <div class="text-center">
                        <span class="badge_service_name"><?= htmlspecialchars($service) ?></span>
                    </div>
                <?php endif; ?>

                <h1 class="blog-title">
                    <span id="title-en"><?= $title ?></span>
                    <span id="title-te" style="display:none;"><?= $telugu_TeTitle = !empty($telugu_title) ? $telugu_title : $title ?></span>
                </h1>
            </div>
        </section>

        <div class="container fullblog_article_container">
            <?php if (!empty($video) || !empty($main_image)): ?>
                <div class="fullblog_media">
                <?php if (!empty($video)): ?>
                    <video controls>
                        <source src="./admin/uploads/videos/<?= $video ?>" type="video/mp4">
                    </video>
                <?php elseif (!empty($main_image)): ?>
                    <img src="./admin/uploads/photos/<?= $main_image ?>" alt="<?= htmlspecialchars($title) ?>">
                <?php endif; ?>
                </div>
            <?php endif; ?>

            <article class="fullblog_article">
                <div class="main-content">
                    <div id="main-en"><?= $main_content ?></div>
                    <div id="main-te" style="display:none;"><?= $telugu_main_content ?></div>
                </div>

                <div class="full-content">
                    <div id="full-en"><?= $full_content ?></div>
                    <div id="full-te" style="display:none;"><?= $telugu_full_content ?></div>
                </div>

                <div class="fullblog_reactions">
                    <button id="like-btn" class="btn" type="button"><i class="fas fa-thumbs-up me-2" aria-hidden="true"></i>Like (<span id="like-count"><?= $likes_count ?></span>)</button>
                    <button id="dislike-btn" class="btn" type="button"><i class="fas fa-thumbs-down me-2" aria-hidden="true"></i>Dislike (<span id="dislike-count"><?= $dislikes_count ?></span>)</button>
                </div>
            </article>
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
                        $link = !empty($row['slug']) ? (preg_match('/^[a-zA-Z0-9_-]+$/D', $row['slug']) ? './' : 'fullblog.php?slug=') . rawurlencode($row['slug']) : 'fullblog.php?id=' . (int) $row['id'];
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




    <!-- <section class="fullblog_related">
        <div class="container">
            <div class="fullblog_related_heading">
                <span>More from our journal</span>
                <h2>Latest Blogs</h2>
                <p>Explore more dental care insights from our team.</p>
            </div>
            <div class="swiper blog-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, title, slug, main_image FROM blogs ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sidebar_image_path = !empty($row['main_image']) ? "./admin/uploads/photos/" . rawurlencode($row['main_image']) : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";
                            $title_short = strlen($row['title']) > 50 ? substr($row['title'], 0, 50) . '...' : $row['title'];
                            $related_title = htmlspecialchars($title_short, ENT_QUOTES, 'UTF-8');
                            $related_image = htmlspecialchars($sidebar_image_path, ENT_QUOTES, 'UTF-8');
                            $related_url = !empty($row['slug']) ? (preg_match('/^[a-zA-Z0-9_-]+$/D', $row['slug']) ? './' : 'fullblog.php?slug=') . rawurlencode($row['slug']) : 'fullblog.php?id=' . (int) $row['id'];

                            echo "
                            <div class='swiper-slide'>
                                <article class='fullblog_related_card'>
                                    <a class='fullblog_related_image' href='{$related_url}'>
                                        <img src='{$related_image}' alt='{$related_title}'>
                                    </a>
                                    <a class='fullblog_related_title' href='{$related_url}'>{$related_title}</a>
                                </article>
                            </div>";
                        }
                    } else {
                        echo "<p>No blog posts found.</p>";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
            <div class="fullblog_related_controls" aria-label="Latest blogs carousel controls">
                <button type="button" class="blog-swiper-button-prev" aria-label="Previous blogs"><i class="fas fa-chevron-left" aria-hidden="true"></i></button>
                <div class="swiper-pagination blog-swiper-pagination" aria-hidden="true"></div>
                <button type="button" class="blog-swiper-button-next" aria-label="Next blogs"><i class="fas fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>
    </section> -->
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
                delay: 3000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".blog-swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".blog-swiper-button-next",
                prevEl: ".blog-swiper-button-prev"
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


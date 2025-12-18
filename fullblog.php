<?php
include './db.connection/db_connection.php';

// GET BLOG ID
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    echo "Invalid Blog ID";
    exit;
}

// ---------------------------------------------
// FETCH BLOG DATA (SERVICE INCLUDED)
// ---------------------------------------------
$stmt = $conn->prepare("
    SELECT 
        title, main_content, full_content, 
        title_image, main_image, video, 
        telugu_title, telugu_main_content, telugu_full_content,
        section1_image,
        service
    FROM blogs 
    WHERE id = ?
");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$stmt->bind_result(
    $title,
    $main_content,
    $full_content,
    $title_image,
    $main_image,
    $video,
    $telugu_title,
    $telugu_main_content,
    $telugu_full_content,
    $section1_image,
    $service
);
$stmt->fetch();
$stmt->close();

// ---------------------------------------------
// FETCH LIKE / DISLIKE COUNTS
// ---------------------------------------------
$count_sql = "SELECT 
                SUM(reaction='like') AS likes,
                SUM(reaction='dislike') AS dislikes
              FROM blog_reactions
              WHERE blog_id = ?";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $blog_id);
$count_stmt->execute();
$count_stmt->bind_result($likes_count, $dislikes_count);
$count_stmt->fetch();
$count_stmt->close();

$conn->close();
?>

<?php include 'header.php'; ?>

<main>
    <div class="container blog-detailed" style="padding-top: 50px;">

        <!-- Language buttons -->
        <div class="d-flex justify-content-center mb-3">
            <button id="english-btn" class="lang-btn btn btn-sm me-2 english-btn">English</button>
            <button id="telugu-btn" class="lang-btn btn btn-sm telugu-btn mx-4">తెలుగు</button>
        </div>


        <?php if (!empty($service)) { ?>
            <div class="text-center mb-3">
                <span class="badge_service_name px-4 py-2">
                    <?= htmlspecialchars($service) ?>
                </span>
            </div>
        <?php } ?>

        <!-- Image -->
        <div class="text-center mb-4">
            <?php if (!empty($section1_image)): ?>
                <img src="./admin/uploads/photos/<?php echo $section1_image; ?>"
                    class="img-fluid "
                    style="width:600px;
                   ">
            <?php else: ?>
                <!-- <p>No Image Available</p> -->
            <?php endif; ?>
        </div>

        <!-- Video / Image -->
        <div class="d-block d-lg-none"><?php
                                        if (!empty($video)) {
                                            $video_path = "./admin/uploads/videos/{$video}";
                                            echo "<video class='main-video' controls
            style='max-width:100%; height:auto; object-fit:contain; display:block; margin:0 auto;'>
            <source src='{$video_path}' type='video/mp4'>
            Your browser does not support the video tag.
          </video>";
                                        } elseif (!empty($main_image)) {
                                            $main_image_path = "./admin/uploads/photos/{$main_image}";;
                                        }
                                        ?>
        </div>


        <div class="d-none d-lg-block">

            <?php
            if (!empty($video)) {
                $video_path = "./admin/uploads/videos/{$video}";
                echo "<video class='main-video' controls
            style='width:700px; height:425px; object-fit:contain; display:block; margin:0 auto;'>
            <source src='{$video_path}' type='video/mp4'>
            Your browser does not support the video tag.
          </video>";
            } elseif (!empty($main_image)) {
                $main_image_path = "./admin/uploads/photos/{$main_image}";;
            }
            ?>
        </div>




        <!-- SERVICE BADGE -->
        <!-- SERVICE BADGE -->
       


        <!-- Title -->
        <h4 class="blog-title text-center mt-5" style="color:#283779; font-weight:800;">
            <span id="title-en"><?php echo $title; ?></span>
            <span id="title-te" style="display:none;"><?php echo $telugu_title; ?></span>
        </h4>

        <!-- Contents -->
        <div class="main-content " style="text-align:justify;">
            <div id="main-en"><?php echo $main_content; ?></div>
            <div id="main-te" style="display:none;"><?php echo $telugu_main_content; ?></div>
        </div>

        <div class="full-content ">
            <div id="full-en"><?php echo $full_content; ?></div>
            <div id="full-te" style="display:none;"><?php echo $telugu_full_content; ?></div>
        </div>

        <!-- LIKE / DISLIKE -->
        <div class="d-flex justify-content-center mt-4">
            <button id="like-btn" class="btn btn-outline-success me-3">
                👍 Like (<span id="like-count"><?php echo $likes_count ?? 0; ?></span>)
            </button>

            <button id="dislike-btn" class="btn btn-outline-danger">
                👎 Dislike (<span id="dislike-count"><?php echo $dislikes_count ?? 0; ?></span>)
            </button>
        </div>

    </div>







    <div class="container">
        <div class="blogs_side my-5">
            <div class="side-bar">
                <h1 class="d-flex justify-content-center my-3">LATEST BLOGS</h1>
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
</main>

<?php include 'footer.php'; ?>

<!-- LANGUAGE SWITCH SCRIPT -->
<script>
    document.getElementById("english-btn").onclick = function() {
        document.getElementById("title-en").style.display = "block";
        document.getElementById("main-en").style.display = "block";
        document.getElementById("full-en").style.display = "block";

        document.getElementById("title-te").style.display = "none";
        document.getElementById("main-te").style.display = "none";
        document.getElementById("full-te").style.display = "none";
    };

    document.getElementById("telugu-btn").onclick = function() {
        document.getElementById("title-en").style.display = "none";
        document.getElementById("main-en").style.display = "none";
        document.getElementById("full-en").style.display = "none";

        document.getElementById("title-te").style.display = "block";
        document.getElementById("main-te").style.display = "block";
        document.getElementById("full-te").style.display = "block";
    };
</script>

<!-- LIKE / DISLIKE SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const blogId = <?php echo json_encode($blog_id); ?>;
        const likeBtn = document.getElementById("like-btn");
        const dislikeBtn = document.getElementById("dislike-btn");

        let hasVoted = localStorage.getItem("blog_vote_" + blogId);

        if (hasVoted) {
            likeBtn.disabled = true;
            dislikeBtn.disabled = true;
        }

        function vote(type) {

            if (hasVoted) return;

            fetch("update_vote.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `blog_id=${blogId}&vote_type=${type}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {

                        document.getElementById("like-count").textContent = data.new_likes;
                        document.getElementById("dislike-count").textContent = data.new_dislikes;

                        localStorage.setItem("blog_vote_" + blogId, type);
                        likeBtn.disabled = true;
                        dislikeBtn.disabled = true;

                    } else {
                        alert("Vote Failed");
                    }
                })
                .catch(() => alert("Error while voting"));
        }

        likeBtn.onclick = () => vote("like");
        dislikeBtn.onclick = () => vote("dislike");

    });
</script>


<script>
    // Initialize Swiper
    var blogSwiper = new Swiper(".blog-swiper", {
        slidesPerView: 3,
        spaceBetween: 20,
        loop: true,
        grabCursor: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".blog-swiper-button-next",
            prevEl: ".blog-swiper-button-prev",
        },
        pagination: {
            el: ".blog-swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            520: {
                slidesPerView: 2
            },
            950: {
                slidesPerView: 3
            },
        },
    });
</script>

</body>

</html>
<?php
// DB Connection
include '../../db.connection/db_connection.php';

// Blog ID
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    echo "Invalid blog ID";
    exit;
}

// ---------------------------------------------
// FETCH BLOG DATA (UPDATED - ALL FIELDS)
// ---------------------------------------------
$stmt = $conn->prepare("
    SELECT 
        title, slug, main_content, full_content,
        service,
        telugu_title, telugu_main_content, telugu_full_content,
        hashtags, keypoints,
        title_image, main_image, video,
        section1_content, section1_image
    FROM blogs
    WHERE id = ?
");

$stmt->bind_param("i", $blog_id);
$stmt->execute();
$stmt->bind_result(
    $title,
    $slug,
    $main_content,
    $full_content,
    $service,
    $telugu_title,
    $telugu_main_content,
    $telugu_full_content,
    $hashtags,
    $keypoints,
    $title_image,
    $main_image,
    $video,
    $section1_content,
    $section1_image
);
$stmt->fetch();
$stmt->close();

// ---------------------------------------------
// JSON → STRING
// ---------------------------------------------
$hashtags_array = json_decode($hashtags, true) ?? [];
$keypoints_array = json_decode($keypoints, true) ?? [];

$hashtags_string = implode(',', $hashtags_array);
$keypoints_string = implode(',', $keypoints_array);

// ---------------------------------------------
// FETCH SERVICES
// ---------------------------------------------
$services = [];
$service_result = $conn->query("SELECT service_name FROM services ORDER BY service_name ASC");

if ($service_result && $service_result->num_rows > 0) {
    while ($row = $service_result->fetch_assoc()) {
        $services[] = $row['service_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Blog</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'navbar.php'; ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Edit Blog</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <form id="editblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">

                                <!-- TITLE -->
                                <div class="mb-3">
                                    <label>Title (English)</label>
                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
                                </div>

                                <!-- SLUG -->
                                <div class="mb-3">
                                    <label>Slug</label>
                                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($slug) ?>" required>
                                </div>

                                <!-- SERVICE -->
                                <div class="mb-3">
                                    <label>Service</label>
                                    <select name="service" class="form-control" required>
                                        <option value="">Select Service</option>
                                        <?php foreach ($services as $s) { ?>
                                            <option value="<?= $s ?>" <?= ($service == $s) ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- MAIN CONTENT -->
                                <div class="mb-3">
                                    <label>Main Content (English)</label>
                                    <div id="mainEditor" style="height:200px"></div>
                                    <input type="hidden" name="main_content" id="mainContentData">
                                </div>

                                <!-- FULL CONTENT -->
                                <div class="mb-3">
                                    <label>Full Content (English)</label>
                                    <div id="fullEditor" style="height:300px"></div>
                                    <input type="hidden" name="full_content" id="fullContentData">
                                </div>

                                <!-- TELUGU -->
                                <div class="mb-3">
                                    <label>Telugu Title</label>
                                    <input type="text" name="telugu_title" class="form-control" value="<?= htmlspecialchars($telugu_title) ?>">
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Main Content</label>
                                    <div id="teluguMainEditor" style="height:200px"></div>
                                    <input type="hidden" name="telugu_main_content" id="teluguMainContentData">
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Full Content</label>
                                    <div id="teluguFullEditor" style="height:300px"></div>
                                    <input type="hidden" name="telugu_full_content" id="teluguFullContentData">
                                </div>

                                <!-- HASHTAGS -->
                                <div class="mb-3">
                                    <label>Hashtags</label>
                                    <input type="text" name="hashtags" class="form-control" value="<?= htmlspecialchars($hashtags_string) ?>">
                                </div>

                                <!-- KEYPOINTS -->
                                <div class="mb-3">
                                    <label>Keypoints</label>
                                    <input type="text" name="keypoints" class="form-control" value="<?= htmlspecialchars($keypoints_string) ?>">
                                </div>

                                <!-- TITLE IMAGE -->
                                <div class="mb-3">
                                    <label>Title Image</label>
                                    <input type="file" name="title_image" class="form-control">
                                    <?php if ($title_image) { ?>
                                        <img src="../../uploads/photos/<?= $title_image ?>" style="max-width:150px;">
                                    <?php } ?>
                                </div>

                                <!-- MAIN IMAGE -->
                                <div class="mb-3">
                                    <label>Main Image</label>
                                    <input type="file" name="main_image" class="form-control">
                                    <?php if ($main_image) { ?>
                                        <img src="../../uploads/photos/<?= $main_image ?>" style="max-width:200px;">
                                    <?php } ?>
                                </div>

                                <!-- VIDEO -->
                                <div class="mb-3">
                                    <label>Video</label>
                                    <input type="file" name="video" class="form-control">
                                    <?php if ($video) { ?>
                                        <a href="../../uploads/videos/<?= $video ?>" target="_blank">View Current Video</a>
                                    <?php } ?>
                                </div>

                                <!-- SECTION 1 -->
                                <div class="mb-3">
                                    <label>Section 1 Content</label>
                                    <textarea name="section1_content" class="form-control"><?= htmlspecialchars($section1_content) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Section 1 Image</label>
                                    <input type="file" name="section1_image" class="form-control">
                                    <?php if ($section1_image) { ?>
                                        <img src="../../uploads/photos/<?= $section1_image ?>" style="max-width:200px;">
                                    <?php } ?>
                                </div>

                                <!-- HIDDEN -->
                                <input type="hidden" name="id" value="<?= $blog_id ?>">
                                <input type="hidden" name="old_title_image" value="<?= $title_image ?>">
                                <input type="hidden" name="old_main_image" value="<?= $main_image ?>">
                                <input type="hidden" name="old_section1_image" value="<?= $section1_image ?>">

                                <button type="submit" class="btn btn-success">Update</button>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        const quillMain = new Quill('#mainEditor', {
            theme: 'snow'
        });
        const quillFull = new Quill('#fullEditor', {
            theme: 'snow'
        });
        const tqMain = new Quill('#teluguMainEditor', {
            theme: 'snow'
        });
        const tqFull = new Quill('#teluguFullEditor', {
            theme: 'snow'
        });

        // LOAD DATA
        quillMain.root.innerHTML = <?= json_encode($main_content) ?>;
        quillFull.root.innerHTML = <?= json_encode($full_content) ?>;
        tqMain.root.innerHTML = <?= json_encode($telugu_main_content) ?>;
        tqFull.root.innerHTML = <?= json_encode($telugu_full_content) ?>;

        document.getElementById('editblogform').onsubmit = function() {
            document.getElementById('mainContentData').value = quillMain.root.innerHTML;
            document.getElementById('fullContentData').value = quillFull.root.innerHTML;
            document.getElementById('teluguMainContentData').value = tqMain.root.innerHTML;
            document.getElementById('teluguFullContentData').value = tqFull.root.innerHTML;
        };
    </script>

</body>

</html>
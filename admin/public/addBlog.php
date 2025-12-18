<?php
include '../../db.connection/db_connection.php';

function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

$allowed_extensions = ['jpg','jpeg','png','gif','webp','svg'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $blog_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $title        = $_POST['title'] ?? '';
    $main_content = $_POST['main_content'] ?? '';
    $full_content = $_POST['full_content'] ?? '';
    $service      = $_POST['service'] ?? '';

    $telugu_title        = $_POST['telugu_title'] ?? '';
    $telugu_main_content = $_POST['telugu_main_content'] ?? '';
    $telugu_full_content = $_POST['telugu_full_content'] ?? '';

    $section1_content = $_POST['section1_content'] ?? '';
    $section2_content = $_POST['section2_content'] ?? '';
    $section3_content = $_POST['section3_content'] ?? '';

    // OLD IMAGES (FROM HIDDEN INPUTS)
    $old_title_image    = $_POST['old_title_image'] ?? '';
    $old_main_image     = $_POST['old_main_image'] ?? '';
    $old_section1_image = $_POST['old_section1_image'] ?? '';
    $old_section2_image = $_POST['old_section2_image'] ?? '';
    $old_section3_image = $_POST['old_section3_image'] ?? '';
    $old_video          = $_POST['old_video'] ?? '';

    if (!$title || !$main_content || !$full_content || !$service) {
        exit("Required fields missing");
    }

    function uploadImage($key, $dir, $allowed_extensions, $oldFile = '') {
        if (empty($_FILES[$key]['name'])) {
            return $oldFile; // KEEP OLD IMAGE
        }

        $ext = strtolower(pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_extensions)) {
            exit("Invalid file type");
        }

        $path = __DIR__ . "/../uploads/$dir/";
        if (!is_dir($path)) mkdir($path, 0777, true);

        $name = generateUniqueFileName($_FILES[$key]['name']);
        move_uploaded_file($_FILES[$key]['tmp_name'], $path . $name);

        return $name;
    }

    // IMAGE HANDLING (SMART)
    $title_image  = uploadImage('title_image', 'photos', $allowed_extensions, $old_title_image);
    $main_image   = uploadImage('main_image', 'photos', $allowed_extensions, $old_main_image);
    $section1_img = uploadImage('section1_image', 'photos', $allowed_extensions, $old_section1_image);
    $section2_img = uploadImage('section2_image', 'photos', $allowed_extensions, $old_section2_image);
    $section3_img = uploadImage('section3_image', 'photos', $allowed_extensions, $old_section3_image);

    // VIDEO
    if (!empty($_FILES['video']['name'])) {
        $vpath = __DIR__ . "/../uploads/videos/";
        if (!is_dir($vpath)) mkdir($vpath, 0777, true);

        $video = generateUniqueFileName($_FILES['video']['name']);
        move_uploaded_file($_FILES['video']['tmp_name'], $vpath . $video);
    } else {
        $video = $old_video;
    }

    // --------------------------------
    // UPDATE
    // --------------------------------
    if ($blog_id > 0) {

        $stmt = $conn->prepare("
            UPDATE blogs SET
                title=?, main_content=?, full_content=?,
                telugu_title=?, telugu_main_content=?, telugu_full_content=?,
                title_image=?, main_image=?, video=?, service=?,
                section1_content=?, section1_image=?,
                section2_content=?, section2_image=?,
                section3_content=?, section3_image=?
            WHERE id=?
        ");

        $stmt->bind_param(
            "ssssssssssssssssi",
            $title, $main_content, $full_content,
            $telugu_title, $telugu_main_content, $telugu_full_content,
            $title_image, $main_image, $video, $service,
            $section1_content, $section1_img,
            $section2_content, $section2_img,
            $section3_content, $section3_img,
            $blog_id
        );

    } 
    // --------------------------------
    // INSERT
    // --------------------------------
    else {

        $stmt = $conn->prepare("
            INSERT INTO blogs
            (title, main_content, full_content,
             telugu_title, telugu_main_content, telugu_full_content,
             title_image, main_image, video, service,
             section1_content, section1_image,
             section2_content, section2_image,
             section3_content, section3_image, created_at)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())
        ");

        $stmt->bind_param(
            "ssssssssssssssss",
            $title, $main_content, $full_content,
            $telugu_title, $telugu_main_content, $telugu_full_content,
            $title_image, $main_image, $video, $service,
            $section1_content, $section1_img,
            $section2_content, $section2_img,
            $section3_content, $section3_img
        );
    }

    if ($stmt->execute()) {
        header("Location: allBlog.php");
        exit;
    } else {
        echo $stmt->error;
    }
}
?>

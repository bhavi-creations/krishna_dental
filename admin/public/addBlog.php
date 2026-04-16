<?php
include '../../db.connection/db_connection.php';

mysqli_set_charset($conn, "utf8mb4");

function generateUniqueFileName($fileName)
{
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $title = $_POST['title'] ?? '';
    $slug = $_POST['slug'] ?? '';

    $main_content = isset($_POST['main_content']) ? trim($_POST['main_content']) : '';
    $full_content = isset($_POST['full_content']) ? trim($_POST['full_content']) : '';

    $service = $_POST['service'] ?? '';

    $telugu_title = $_POST['telugu_title'] ?? '';
    $telugu_main_content = isset($_POST['telugu_main_content']) ? trim($_POST['telugu_main_content']) : '';
    $telugu_full_content = isset($_POST['telugu_full_content']) ? trim($_POST['telugu_full_content']) : '';

    $section1_content = $_POST['section1_content'] ?? '';
    $section2_content = $_POST['section2_content'] ?? '';
    $section3_content = $_POST['section3_content'] ?? '';

    $hashtags = $_POST['hashtags'] ?? '';
    $keypoints = $_POST['keypoints'] ?? '';

    $hashtags_json = json_encode(array_map('trim', explode(',', $hashtags)));
    $keypoints_json = json_encode(array_map('trim', explode(',', $keypoints)));

    if (empty($title) || empty($main_content) || empty($full_content) || empty($service) || empty($slug)) {
        die("Error: Required fields missing");
    }

    function uploadImage($fileKey, $directoryName, $allowed_extensions)
    {
        $path = '';
        if (!empty($_FILES[$fileKey]['name'])) {

            $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed_extensions)) {
                die("Invalid file type for $fileKey");
            }

            $directory = __DIR__ . "/../uploads/$directoryName/";
            if (!is_dir($directory)) mkdir($directory, 0777, true);

            $fileName = generateUniqueFileName($_FILES[$fileKey]['name']);

            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $directory . $fileName)) {
                $path = $fileName;
            } else {
                die("Upload failed: $fileKey");
            }
        }
        return $path;
    }

    $title_image_path = uploadImage('title_image', 'photos', $allowed_extensions);
    $main_image_path  = uploadImage('main_image', 'photos', $allowed_extensions);

    // Video upload
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_directory = __DIR__ . "/../uploads/videos/";
        if (!is_dir($video_directory)) mkdir($video_directory, 0777, true);

        $video_name = generateUniqueFileName($_FILES['video']['name']);

        if (move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            $video_path = $video_name;
        } else {
            die("Video upload failed");
        }
    }

    $section1_image = uploadImage('section1_image', 'photos', $allowed_extensions);
    $section2_image = uploadImage('section2_image', 'photos', $allowed_extensions);
    $section3_image = uploadImage('section3_image', 'photos', $allowed_extensions);

    // ================= UPDATE =================
    if ($blog_id > 0) {

        $stmt = $conn->prepare("UPDATE blogs 
        SET title=?, slug=?, main_content=?, full_content=?, 
            telugu_title=?, telugu_main_content=?, telugu_full_content=?,
            hashtags=?, keypoints=?,
            title_image=?, main_image=?, video=?, 
            service=?,
            section1_content=?, section1_image=?,
            section2_content=?, section2_image=?,
            section3_content=?, section3_image=?
        WHERE id=?");

        if (!$stmt) {
            die("SQL ERROR: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssssssssi",
            $title,
            $slug,
            $main_content,
            $full_content,
            $telugu_title,
            $telugu_main_content,
            $telugu_full_content,
            $hashtags_json,
            $keypoints_json,
            $title_image_path,
            $main_image_path,
            $video_path,
            $service,
            $section1_content,
            $section1_image,
            $section2_content,
            $section2_image,
            $section3_content,
            $section3_image,
            $blog_id
        );
    }

    // ================= INSERT =================
    else {

        $stmt = $conn->prepare("INSERT INTO blogs 
        (title, slug, main_content, full_content, telugu_title, telugu_main_content, telugu_full_content,
         hashtags, keypoints,
         title_image, main_image, video, service,
         section1_content, section1_image,
         section2_content, section2_image,
         section3_content, section3_image, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if (!$stmt) {
            die("SQL ERROR: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssssssss",
            $title,
            $slug,
            $main_content,
            $full_content,
            $telugu_title,
            $telugu_main_content,
            $telugu_full_content,
            $hashtags_json,
            $keypoints_json,
            $title_image_path,
            $main_image_path,
            $video_path,
            $service,
            $section1_content,
            $section1_image,
            $section2_content,
            $section2_image,
            $section3_content,
            $section3_image
        );
    }

    if ($stmt->execute()) {
        header("Location: allBlog.php");
        exit();
    } else {
        die("EXECUTE ERROR: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
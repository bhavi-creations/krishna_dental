<?php
// Database connection
include '../../db.connection/db_connection.php';

// Function to generate a unique file name
function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

// Allowed image types
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = $_POST['title'] ?? '';
    $main_content = $_POST['main_content'] ?? '';
    $full_content = $_POST['full_content'] ?? '';
    $service = $_POST['service'] ?? '';
    $logo_link = $_POST['logo_link'] ?? '';

    // Telugu fields
    $telugu_title = $_POST['telugu_title'] ?? '';
    $telugu_main_content = $_POST['telugu_main_content'] ?? '';
    $telugu_full_content = $_POST['telugu_full_content'] ?? '';

    // Section contents
    $section1_content = $_POST['section1_content'] ?? '';
    $section2_content = $_POST['section2_content'] ?? '';
    $section3_content = $_POST['section3_content'] ?? '';

    // Required fields check
    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Error: Title, Main Content, Full Content, and Service cannot be empty.");
    }

    // Helper function for image upload
    function uploadImage($fileKey, $directoryName, $allowed_extensions) {
        $path = '';
        if (!empty($_FILES[$fileKey]['name'])) {
            $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_extensions)) {
                die("Error: Invalid file type for $fileKey.");
            }

            $directory = __DIR__ . "/../uploads/$directoryName/";
            if (!is_dir($directory)) mkdir($directory, 0777, true);

            $fileName = generateUniqueFileName($_FILES[$fileKey]['name']);
            $path = $fileName;

            if (!move_uploaded_file($_FILES[$fileKey]['tmp_name'], $directory . $fileName)) {
                die("Error uploading $fileKey.");
            }
        }
        return $path;
    }

    // Upload main assets
    $title_image_path = uploadImage('title_image', 'photos', $allowed_extensions);
    $main_image_path  = uploadImage('main_image', 'photos', $allowed_extensions);
    $logo_path        = uploadImage('logo', 'logos', $allowed_extensions);

    // Upload video
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_directory = __DIR__ . "/../uploads/videos/";
        if (!is_dir($video_directory)) mkdir($video_directory, 0777, true);

        $video_name = generateUniqueFileName($_FILES['video']['name']);
        $video_path = $video_name;

        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            die("Error uploading video.");
        }
    }

    // Upload section images
    $section1_image = uploadImage('section1_image', 'photos', $allowed_extensions);
    $section2_image = uploadImage('section2_image', 'photos', $allowed_extensions);
    $section3_image = uploadImage('section3_image', 'photos', $allowed_extensions);

    // Prepare SQL (Insert or Update)
    if ($blog_id > 0) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE blogs 
            SET title=?, main_content=?, full_content=?, 
                telugu_title=?, telugu_main_content=?, telugu_full_content=?,
                title_image=?, main_image=?, video=?, 
                service=?, logo=?, logo_link=?,
                section1_content=?, section1_image=?,
                section2_content=?, section2_image=?,
                section3_content=?, section3_image=?
            WHERE id=?");

        $stmt->bind_param(
            "ssssssssssssssssssi",
            $title, $main_content, $full_content,
            $telugu_title, $telugu_main_content, $telugu_full_content,
            $title_image_path, $main_image_path, $video_path,
            $service, $logo_path, $logo_link,
            $section1_content, $section1_image,
            $section2_content, $section2_image,
            $section3_content, $section3_image,
            $blog_id
        );

    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO blogs 
            (title, main_content, full_content, telugu_title, telugu_main_content, telugu_full_content,
             title_image, main_image, video, service, logo, logo_link,
             section1_content, section1_image,
             section2_content, section2_image,
             section3_content, section3_image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->bind_param(
            "ssssssssssssssssss",
            $title, $main_content, $full_content, $telugu_title, $telugu_main_content, $telugu_full_content,
            $title_image_path, $main_image_path, $video_path,
            $service, $logo_path, $logo_link,
            $section1_content, $section1_image,
            $section2_content, $section2_image,
            $section3_content, $section3_image
        );
    }

    // Execute SQL
    if ($stmt->execute()) {
        header("Location: allBlog.php");
        exit();
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>

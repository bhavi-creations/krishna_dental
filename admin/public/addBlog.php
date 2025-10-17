<?php
// ======================================
// ðŸ§© ENABLE SAFE ERROR LOGGING (FOR LIVE SERVER)
// ======================================
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log'); // Logs all errors to this file
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 0 on production if you donâ€™t want to show errors

// ======================================
// ðŸ§© DATABASE CONNECTION
// ======================================
include __DIR__ . '/../../db.connection/db_connection.php';

// ======================================
// ðŸ§© ALLOWED FILE FORMATS
// ======================================
$allowedImageFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// ======================================
// ðŸ§© FUNCTION: Generate Unique File Name
// ======================================
function generateUniqueFileName($fileName)
{
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid('file_') . '_' . time() . '.' . $ext;
}

// ======================================
// ðŸ§© FUNCTION: Upload File
// ======================================
function uploadFile($fileKey, $uploadDir, $allowedFormats = [])
{
    if (!empty($_FILES[$fileKey]['name'])) {

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                error_log("Failed to create directory: " . $uploadDir);
                die("Error: Failed to create upload directory.");
            }
        }

        $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
        if (!empty($allowedFormats) && !in_array($ext, $allowedFormats)) {
            die("Error: Invalid file format for $fileKey. Allowed: " . implode(', ', $allowedFormats));
        }

        if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            die("Upload error for $fileKey: " . $_FILES[$fileKey]['error']);
        }

        $fileName = generateUniqueFileName($_FILES[$fileKey]['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
            return $fileName; // store only filename in DB
        } else {
            error_log("Failed to move uploaded file for $fileKey to $targetPath");
            die("Error uploading $fileKey.");
        }
    }
    return '';
}

// ======================================
// ðŸ§© MAIN SCRIPT
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $blog_id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title        = trim($_POST['title'] ?? '');
    $main_content = trim($_POST['main_content'] ?? '');
    $full_content = trim($_POST['full_content'] ?? '');
    $service      = trim($_POST['service'] ?? '');

    // Validation
    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Error: Title, Main Content, Full Content, and Service cannot be empty.");
    }

    // Directories (absolute path)
    $uploadsDir = __DIR__ . "/../uploads/photos/";
    $videosDir  = __DIR__ . "/../uploads/videos/";

    // Upload main files
    $main_image_path  = uploadFile('main_image', $uploadsDir, $allowedImageFormats);
    $video_path       = uploadFile('video', $videosDir);
    $title_image_path = uploadFile('title_image', $uploadsDir, $allowedImageFormats);

    // Section contents and images
    $section_contents = [];
    $section_images   = [];
    for ($i = 1; $i <= 3; $i++) {
        $section_contents[$i] = $_POST["section{$i}_content"] ?? '';
        $section_images[$i]   = uploadFile("section{$i}_image", $uploadsDir, $allowedImageFormats);
    }

    // Preserve existing files if editing
    if ($blog_id > 0) {
        $existing = $conn->query("SELECT * FROM blogs WHERE id=$blog_id");
        if ($existing && $existing->num_rows > 0) {
            $existing = $existing->fetch_assoc();

            $main_image_path  = $main_image_path ?: ($existing['main_image'] ?? '');
            $video_path       = $video_path ?: ($existing['video'] ?? '');
            $title_image_path = $title_image_path ?: ($existing['title_image'] ?? '');
            for ($i = 1; $i <= 3; $i++) {
                $section_images[$i] = $section_images[$i] ?: ($existing["section{$i}_image"] ?? '');
            }
        } else {
            die("Error: Blog not found for editing.");
        }
    }

    // ======================================
    // ðŸ§© SQL QUERIES
    // ======================================
    if ($blog_id > 0) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE blogs 
            SET title=?, main_content=?, full_content=?, title_image=?, main_image=?, video=?, service=?,
                section1_content=?, section1_image=?, 
                section2_content=?, section2_image=?,
                section3_content=?, section3_image=?
            WHERE id=?");

        if (!$stmt) {
            error_log("Prepare failed (UPDATE): " . $conn->error);
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssssi",
            $title,
            $main_content,
            $full_content,
            $title_image_path,
            $main_image_path,
            $video_path,
            $service,
            $section_contents[1],
            $section_images[1],
            $section_contents[2],
            $section_images[2],
            $section_contents[3],
            $section_images[3],
            $blog_id
        );

    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO blogs 
            (title, main_content, full_content, title_image, main_image, video, service,
             section1_content, section1_image,
             section2_content, section2_image,
             section3_content, section3_image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if (!$stmt) {
            error_log("Prepare failed (INSERT): " . $conn->error);
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssss",
            $title,
            $main_content,
            $full_content,
            $title_image_path,
            $main_image_path,
            $video_path,
            $service,
            $section_contents[1],
            $section_images[1],
            $section_contents[2],
            $section_images[2],
            $section_contents[3],
            $section_images[3]
        );
    }

    // ======================================
    // ðŸ§© EXECUTE AND REDIRECT
    // ======================================
    if ($stmt->execute()) {
        header("Location: allBlog.php");
        exit();
    } else {
        error_log("Execute error: " . $stmt->error);
        die("Execute Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

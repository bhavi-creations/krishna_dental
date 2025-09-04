<?php
// Database connection
include '../../db.connection/db_connection.php';

// Function to generate a unique file name
function generateUniqueFileName($fileName)
{
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input
    $blog_id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title        = isset($_POST['title']) ? trim($_POST['title']) : '';
    $main_content = isset($_POST['main_content']) ? trim($_POST['main_content']) : '';
    $full_content = isset($_POST['full_content']) ? trim($_POST['full_content']) : '';
    $service      = isset($_POST['service']) ? trim($_POST['service']) : '';

    // Validate required fields
    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Error: Title, Main Content, Full Content, and Service cannot be empty.");
    }

    // ========== Handle Title Image ==========
    $title_image_path = 'default.jpg';
    if (!empty($_FILES['title_image']['name'])) {
        $title_image_directory = __DIR__ . "/admin/public/uploads/photos/"; // ✅ Correct path

        if (!is_dir($title_image_directory)) {
            mkdir($title_image_directory, 0777, true);
        }

        $title_image_name = generateUniqueFileName($_FILES['title_image']['name']);
        $title_image_path = $title_image_name;

        if (!move_uploaded_file($_FILES['title_image']['tmp_name'], $title_image_directory . $title_image_name)) {
            die("Error uploading title image. Path tried: " . $title_image_directory . $title_image_name);
        }
    }

    // ========== Handle Main Image ==========
    $main_image_path = 'default.jpg';
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_directory = __DIR__ . "/admin/public/uploads/photos/"; // ✅ Correct path

        if (!is_dir($main_image_directory)) {
            mkdir($main_image_directory, 0777, true);
        }

        $main_image_name = generateUniqueFileName($_FILES['main_image']['name']);
        $main_image_path = $main_image_name;

        if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_directory . $main_image_name)) {
            die("Error uploading main image. Path tried: " . $main_image_directory . $main_image_name);
        }
    }

    // ========== Handle Video ==========
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_directory = __DIR__ . "/admin/public/uploads/videos/"; // ✅ Correct path

        if (!is_dir($video_directory)) {
            mkdir($video_directory, 0777, true);
        }

        $video_name = generateUniqueFileName($_FILES['video']['name']);
        $video_path = $video_name;

        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            die("Error uploading video. Path tried: " . $video_directory . $video_name);
        }
    }

    // ========== Insert or Update ==========
    if ($blog_id > 0) {
        // Update existing blog
        $stmt = $conn->prepare("UPDATE blogs 
            SET title = ?, main_content = ?, full_content = ?, title_image = ?, main_image = ?, video = ?, service = ? 
            WHERE id = ?");
        $stmt->bind_param("sssssssi", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service, $blog_id);
    } else {
        // Insert new blog
        $stmt = $conn->prepare("INSERT INTO blogs (title, main_content, full_content, title_image, main_image, video, service, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service);
    }

    if ($stmt->execute()) {
        header("Location: allBlog.php"); // success redirect
        exit();
    } else {
        echo "Error: " . $stmt->error;
        header("Location: newBlog.php"); // failure redirect
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

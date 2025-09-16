<?php
// Database connection (replace with your actual database connection details)
include '../../db.connection/db_connection.php';

// Function to generate a unique file name
function generateUniqueFileName($fileName)
{
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect inputs safely
    $blog_id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title        = isset($_POST['title']) ? trim($_POST['title']) : '';
    $main_content = isset($_POST['main_content']) ? trim($_POST['main_content']) : '';
    $full_content = isset($_POST['full_content']) ? trim($_POST['full_content']) : '';
    $service      = isset($_POST['service']) ? trim($_POST['service']) : '';

    // Validate required fields
    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Error: Title, Main Content, Full Content, and Service cannot be empty.");
    }

    // ------------------------
    // Handle Title Image Upload
    // ------------------------
    $title_image_path = '';
    if (!empty($_FILES['title_image']['name'])) {
        $title_image_directory = $_SERVER['DOCUMENT_ROOT'] . "/krishna_dental/admin/uploads/photos/";
        if (!is_dir($title_image_directory)) {
            mkdir($title_image_directory, 0777, true);
        }

        if ($_FILES['title_image']['error'] !== UPLOAD_ERR_OK) {
            die("Title image upload error: " . $_FILES['title_image']['error']);
        }

        $title_image_name = generateUniqueFileName($_FILES['title_image']['name']);
        $title_image_path = $title_image_name;

        if (!move_uploaded_file($_FILES['title_image']['tmp_name'], $title_image_directory . $title_image_name)) {
            die("Error uploading title image.");
        }
    }

    // ------------------------
    // Handle Main Image Upload
    // ------------------------
    $main_image_path = '';
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_directory = $_SERVER['DOCUMENT_ROOT'] . "/krishna_dental/admin/uploads/photos/";
        if (!is_dir($main_image_directory)) {
            mkdir($main_image_directory, 0777, true);
        }

        if ($_FILES['main_image']['error'] !== UPLOAD_ERR_OK) {
            die("Main image upload error: " . $_FILES['main_image']['error']);
        }

        $main_image_name = generateUniqueFileName($_FILES['main_image']['name']);
        $main_image_path = $main_image_name;

        if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_directory . $main_image_name)) {
            die("Error uploading main image.");
        }
    }

    // ------------------------
    // Handle Video Upload
    // ------------------------
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_directory = $_SERVER['DOCUMENT_ROOT'] . "/krishna_dental/admin/uploads/videos/";
        if (!is_dir($video_directory)) {
            mkdir($video_directory, 0777, true);
        }

        if ($_FILES['video']['error'] !== UPLOAD_ERR_OK) {
            die("Video upload error: " . $_FILES['video']['error']);
        }

        $video_name = generateUniqueFileName($_FILES['video']['name']);
        $video_path = $video_name;

        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            die("Error uploading video.");
        }
    }

    // ------------------------
    // Insert / Update Blog Post
    // ------------------------
    if ($blog_id > 0) {
        // Update existing blog
        $stmt = $conn->prepare("UPDATE blogs 
            SET title = ?, main_content = ?, full_content = ?, title_image = ?, main_image = ?, video = ?, service = ? 
            WHERE id = ?");
        $stmt->bind_param("sssssssi", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service, $blog_id);
    } else {
        // Insert new blog
        $stmt = $conn->prepare("INSERT INTO blogs 
            (title, main_content, full_content, title_image, main_image, video, service, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service);
    }

    // Execute query
    if ($stmt->execute()) {
        echo "Blog post published/updated successfully!";
        header("Location: allBlog.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
        header("Location: newBlog.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

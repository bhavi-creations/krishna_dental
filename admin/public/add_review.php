<?php
// Database connection (replace with your actual database connection details)
include '../../db.connection/db_connection.php';

// Function to generate a unique file name
function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for existence of $_POST keys to avoid undefined index warnings
    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $main_content = isset($_POST['main_content']) ? $_POST['main_content'] : '';
    $full_content = isset($_POST['full_content']) ? $_POST['full_content'] : '';
    $service = isset($_POST['service']) ? $_POST['service'] : '';  // Capture selected service

    // Ensure required fields are not empty
    if (empty($title) || empty($main_content) || empty($full_content) || empty($service)) {
        die("Error: Title, Main Content, Full Content, and Service cannot be empty.");
    }

    // Handle file uploads for title image and main image
    $title_image_path = '';
    if (!empty($_FILES['title_image']['name'])) {
        $title_image_directory = __DIR__ . "/../uploads/photos/";
        $title_image_name = generateUniqueFileName($_FILES['title_image']['name']);
        $title_image_path = $title_image_name;

        if (!move_uploaded_file($_FILES['title_image']['tmp_name'], $title_image_directory . $title_image_name)) {
            die("Error uploading title image.");
        }
    }

    $main_image_path = '';
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_directory = __DIR__ . "/../uploads/photos/";
        $main_image_name = generateUniqueFileName($_FILES['main_image']['name']);
        $main_image_path = $main_image_name;

        if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_directory . $main_image_name)) {
            die("Error uploading main image.");
        }
    }

    // Handle video upload
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_directory = __DIR__ . "/../uploads/videos/";  // Adjust the upload directory path for videos
        $video_name = generateUniqueFileName($_FILES['video']['name']);
        $video_path = $video_name;  // Store only the filename

        // Ensure the video upload directory exists
        if (!is_dir($video_directory)) {
            mkdir($video_directory, 0777, true);
        }

        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            die("Error uploading video.");
        }
    }

    // Prepare SQL statement based on whether it's an insert or update
    if ($blog_id > 0) {
        // Update existing blog post
        $stmt = $conn->prepare("UPDATE blogs SET title = ?, main_content = ?, full_content = ?, title_image = ?, main_image = ?, video = ?, service = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service, $blog_id);
    } else {
        // Insert new blog post
        $stmt = $conn->prepare("INSERT INTO blogs (title, main_content, full_content, title_image, main_image, video, service, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $title, $main_content, $full_content, $title_image_path, $main_image_path, $video_path, $service);
    }

    // Execute the SQL statement
    if ($stmt->execute()) {
        echo "Blog post published/updated successfully!";
        header("Location: allBlog.php");  // Redirect after successful submission
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

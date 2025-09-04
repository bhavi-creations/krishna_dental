<?php
// Database connection
include '../../db.connection/db_connection.php';

// Function to generate a unique file name
function generateUniqueFileName($fileName) {
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $ext;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title        = mysqli_real_escape_string($conn, $_POST['title']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $content      = mysqli_real_escape_string($conn, $_POST['content']);
    $author       = mysqli_real_escape_string($conn, $_POST['author']);
    $date         = mysqli_real_escape_string($conn, $_POST['date']);
    $status       = isset($_POST['status']) ? intval($_POST['status']) : 0;

    // Define directories (✅ fixed paths)
    $title_image_directory = __DIR__ . "/../uploads/photos/";
    $main_image_directory  = __DIR__ . "/../uploads/photos/";
    $video_directory       = __DIR__ . "/../uploads/videos/";

    // Ensure directories exist
    if (!is_dir($title_image_directory)) mkdir($title_image_directory, 0777, true);
    if (!is_dir($main_image_directory)) mkdir($main_image_directory, 0777, true);
    if (!is_dir($video_directory)) mkdir($video_directory, 0777, true);

    // ========== Handle Title Image ==========
    $title_image_path = 'default.jpg';
    if (!empty($_FILES['title_image']['name'])) {
        $title_image_name = generateUniqueFileName($_FILES['title_image']['name']);
        $title_image_path = $title_image_name;
        if (!move_uploaded_file($_FILES['title_image']['tmp_name'], $title_image_directory . $title_image_name)) {
            die("Error uploading title image.");
        }
    }

    // ========== Handle Main Image ==========
    $main_image_path = 'default.jpg';
    if (!empty($_FILES['main_image']['name'])) {
        $main_image_name = generateUniqueFileName($_FILES['main_image']['name']);
        $main_image_path = $main_image_name;
        if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $main_image_directory . $main_image_name)) {
            die("Error uploading main image.");
        }
    }

    // ========== Handle Video ==========
    $video_path = '';
    if (!empty($_FILES['video']['name'])) {
        $video_name = generateUniqueFileName($_FILES['video']['name']);
        $video_path = $video_name;
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_directory . $video_name)) {
            die("Error uploading video.");
        }
    }

    // Insert query
    $sql = "INSERT INTO blogs (title, description, content, author, date, status, title_image, main_image, video) 
            VALUES ('$title', '$description', '$content', '$author', '$date', '$status', 
                    '$title_image_path', '$main_image_path', '$video_path')";

    if ($conn->query($sql) === TRUE) {
        echo "Blog added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

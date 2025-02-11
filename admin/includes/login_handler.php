<?php
session_start();
require '../../db.connection/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password using MD5
    $password = md5($password);

    // Fetch the user from the database using email and password
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    // Check if user exists and password matches
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['first_name'];
        header('Location: ../public/index.php');
       exit();
    } else {
        echo "error2";
      header('Location: ../public/login.php?error=Invalid email or password');
      exit();
    }
}
?>



 

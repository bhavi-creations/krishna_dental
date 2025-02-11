 
<?php
require '../../db.connection/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];

    // Check if passwords match
    if ($password !== $repeatPassword) {
        header('Location: ../public/register.php?error=Passwords do not match');
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        header('Location: ../public/register.php?error=Email already exists');
        exit();
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE first_name = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        header('Location: ../public/register.php?error=Username already exists');
        exit();
    }

    // Hash the password and insert into database
    $hashedPassword = md5($password);
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $lastName, $email, $hashedPassword])) {
        header('Location: ../public/login.php');
        exit();
    } else {
        header('Location: ../public/register.php?error=Registration failed');
        exit();
    }
}
?>

<?php
 
 session_start();
 include '../../db.connection/db_connection.php';
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$id=$_SESSION['user_id'];
$first_name=$_POST['username'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];
$password=$_POST['password'];
if(!empty($password)){
    $password=md5($password);
$sql = "UPDATE users SET first_name='$first_name',last_name='$last_name',email='$email',password='$password' WHERE id='$id'";
}
else{
    $sql = "UPDATE users SET first_name='$first_name',last_name='$last_name',email='$email' WHERE id='$id'";
}
if ($conn->query($sql) === TRUE) {
    
  echo "Record updated successfully";
  header('Location: profile.php');
} else {
    header('Location: profile.php');
}

$conn->close();
?>
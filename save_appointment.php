<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include './db.connection/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Honeypot Protection Check
if (!empty($_POST['website'])) die("Bot block triggered.");
if (!isset($_SESSION['form_time']) || (time() - $_SESSION['form_time']) < 5) die("Rate limit security active.");

// Google Recaptcha validation
$secretKey = "6Ldws0ktAAAAAD7pIKreribWZJeii1BzFMfk1sr8";
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$recaptchaResponse);
$verifyData = json_decode($verify);

if (empty($recaptchaResponse) || !$verifyData->success) die("Recaptcha verification failed.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim(htmlspecialchars($_POST['name']));
    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $phone = trim($_POST['phone']);
    $date  = $_POST['appointment_date'];
    $slot  = $_POST['time_slot'];
    $msg   = trim(htmlspecialchars($_POST['message']));
    $day   = date('l', strtotime($date));

    if (!$email || empty($name) || empty($phone) || empty($slot)) die("Invalid input details.");

    // Unique Independent Prepared Instance: Final Capacity Cross Validation Guard
    $stmtCap = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = ? AND time_slot = ?");
    if ($stmtCap) {
        $stmtCap->bind_param("ss", $date, $slot);
        $stmtCap->execute();
        $capResult = $stmtCap->get_result()->fetch_assoc();
        $stmtCap->close();

        if ($capResult['total'] >= 3) {
            echo "<script>alert('Validation check failed: This slot has been completely filled up.'); window.location='index.php';</script>";
            exit;
        }
    }

    // Unique Independent Prepared Instance: Data Insert Core Engine
    $stmtWrite = $conn->prepare("INSERT INTO appointments (name, email, phone, appointment_date, time_slot, message) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmtWrite) {
        $stmtWrite->bind_param("ssssss", $name, $email, $phone, $date, $slot, $msg);
        $stmtWrite->execute();
        $stmtWrite->close();
    }

    // PHPMailer dual tracking dispatcher processes
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'appledentalclinic2025@gmail.com';
        $mail->Password   = 'ixdpuydufjsfxaxb'; // App Password logic configurations
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // 1. Dispatch HTML mail notification to Doctor
        $mail->setFrom('appledentalclinic2025@gmail.com', 'Krishna Dental Clinic');
        $mail->addAddress('appledentalclinic2025@gmail.com'); // Admin email targeting receiver 
        $mail->isHTML(true);
        $mail->Subject = 'Alert: New Appointment Booked';
        $mail->Body    = "<h2>New Appointment Received</h2>
                          <p><b>Patient Name:</b> {$name}</p>
                          <p><b>Contact Phone:</b> {$phone}</p>
                          <p><b>Email Address:</b> {$email}</p>
                          <p><b>Booked Date:</b> {$date} ({$day})</p>
                          <p><b>Selected Hour Slot:</b> {$slot}</p>
                          <p><b>Message Notes:</b> {$msg}</p>";
        $mail->send();

        // 2. Clear address lines and Dispatch confirmation receipt mail to Patient
        $mail->clearAddresses();
        $mail->addAddress($email); // Patient email input
        $mail->Subject = 'Appointment Booked Successfully - Krishna Dental Care';
        $mail->Body    = "<h2>Appointment Confirmation ✅</h2>
                          <p>Dear <b>{$name}</b>,</p>
                          <p>Your appointment session has been successfully recorded and processed.</p>
                          <table cellpadding='5' style='border: 1px solid #ccc;'>
                            <tr><td><b>Confirmed Date:</b></td><td>{$date} ({$day})</td></tr>
                            <tr><td><b>Assigned Time:</b></td><td>{$slot}</td></tr>
                            <tr><td><b>Contact Number:</b></td><td>{$phone}</td></tr>
                          </table>
                          <p>Thank you for choosing <b>Krishna Dental Care</b>. We look forward to your visit.</p>";
        $mail->send();

        echo "<script>window.location='thankyou.php';</script>";
        exit;
    } catch (Exception $mailError) {
        // Fallback fallback: Mail configurations error trigger blocks but user safety is sustained
        echo "<script>window.location='thankyou.php';</script>";
        exit;
    }
}
?>
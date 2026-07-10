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

// Honeypot configuration Check
if (!empty($_POST['website'])) {
    die("Bot detected.");
}

// Dynamic timing validation
if (!isset($_SESSION['form_time']) || (time() - $_SESSION['form_time']) < 5) {
    die("Submission too fast. Bot detected.");
}

// Google Recaptcha v2 Server Verification
$secretKey = "6Ldws0ktAAAAAD7pIKreribWZJeii1BzFMfk1sr8";
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$recaptchaResponse);
$responseData = json_decode($verify);

if (empty($recaptchaResponse) || !$responseData->success) {
    die("Please complete the 'I'm not a robot' verification.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim(htmlspecialchars($_POST['name']));
    $email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $phone = trim($_POST['phone']);
    $date  = $_POST['appointment_date'];
    $slot  = $_POST['time_slot'];
    $msg   = trim(htmlspecialchars($_POST['message']));
    $day   = date('l', strtotime($date));

    if (!$email) {
        die("Invalid Email Address.");
    }

    /* ======================
       HOLIDAY MATRIX CHECK
       ====================== */
    $stmtHoliday = $conn->prepare("SELECT holiday_type, reason FROM holidays WHERE holiday_date = ?");
    if ($stmtHoliday) {
        $stmtHoliday->bind_param("s", $date);
        $stmtHoliday->execute();
        $h = $stmtHoliday->get_result();

        if ($h->num_rows > 0) {
            $row  = $h->fetch_assoc();
            $type = trim(strtolower($row['holiday_type'])); // Handled case mismatch safely

            $morningSlots = ["09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM", "12:00 PM - 01:00 PM", "01:00 PM - 02:00 PM"];
            $afternoonSlots = ["02:00 PM - 03:00 PM", "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM", "06:00 PM - 07:00 PM", "07:00 PM - 08:00 PM", "08:00 PM - 09:00 PM"];

            if ($type == 'fullday' || ($type == 'morning' && in_array($slot, $morningSlots)) || ($type == 'afternoon' && in_array($slot, $afternoonSlots))) {
                $stmtHoliday->close();
                echo "<script>alert('Holiday: ".$row['reason']."'); window.location='index.php';</script>";
                exit;
            }
        }
        $stmtHoliday->close();
    }

    /* ======================
       LIVE SLOT CAPACITY CHECK
       ====================== */
    $stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = ? AND time_slot = ?");
    if ($stmtCheck) {
        $stmtCheck->bind_param("ss", $date, $slot);
        $stmtCheck->execute();
        $count = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        if ($count['total'] >= 3) {
            echo "<script>alert('This time slot is FULL'); window.location='index.php';</script>";
            exit;
        }
    }

    /* ======================
       EXECUTE DB INSERTION
       ====================== */
    $stmtInsert = $conn->prepare("INSERT INTO appointments (name, email, phone, appointment_date, time_slot, message) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmtInsert) {
        $stmtInsert->bind_param("ssssss", $name, $email, $phone, $date, $slot, $msg);
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    /* ======================
       PHPMailer Dynamic Dispatch
       ====================== */
    try {
        // Mail to Doctor Setup
        $mailDoctor = new PHPMailer(true);
        $mailDoctor->isSMTP();
        $mailDoctor->Host       = 'smtp.gmail.com';
        $mailDoctor->SMTPAuth   = true;
        $mailDoctor->Username   = 'appledentalclinic2025@gmail.com';
        $mailDoctor->Password   = 'ixdpuydufjsfxaxb'; // App Password
        $mailDoctor->SMTPSecure = 'tls';
        $mailDoctor->Port       = 587;

        $mailDoctor->setFrom('appledentalclinic2025@gmail.com', 'Clinic Appointment System');
        $mailDoctor->addAddress('appledentalclinic2025@gmail.com');
        $mailDoctor->isHTML(true);
        $mailDoctor->Subject = 'New Appointment Booked';
        $mailDoctor->Body    = "<h2>New Appointment Details</h2>
                                <p><strong>Name:</strong> $name</p>
                                <p><strong>Phone:</strong> $phone</p>
                                <p><strong>Email:</strong> $email</p>
                                <p><strong>Date:</strong> $date ($day)</p>
                                <p><strong>Time Slot:</strong> $slot</p>
                                <p><strong>Message:</strong> $msg</p>";
        $mailDoctor->send();

        // Mail to Patient Setup
        $mailPatient = new PHPMailer(true);
        $mailPatient->isSMTP();
        $mailPatient->Host       = 'smtp.gmail.com';
        $mailPatient->SMTPAuth   = true;
        $mailPatient->Username   = 'appledentalclinic2025@gmail.com';
        $mailPatient->Password   = 'ixdpuydufjsfxaxb';
        $mailPatient->SMTPSecure = 'tls';
        $mailPatient->Port       = 587;

        $mailPatient->setFrom('appledentalclinic2025@gmail.com', 'Apple Dental Specialities');
        $mailPatient->addAddress($email);
        $mailPatient->isHTML(true);
        $mailPatient->Subject = 'Appointment Confirmation';
        $mailPatient->Body    = "<h2>Appointment Confirmed ✅</h2>
                                <p>Dear <strong>$name</strong>,</p>
                                <p>Your appointment has been successfully booked.</p>
                                <table cellpadding='6' border='0'>
                                    <tr><td><strong>Date:</strong></td><td>$date ($day)</td></tr>
                                    <tr><td><strong>Time:</strong></td><td>$slot</td></tr>
                                    <tr><td><strong>Phone:</strong></td><td>$phone</td></tr>
                                </table>
                                <p>Thank you for choosing<br><b>Apple Dental Specialities</b>.</p>";
        $mailPatient->send();

        echo "<script>window.location='thankyou.php';</script>";
        exit;

    } catch (Exception $e) {
        echo "Mailer Error details: " . $e->getMessage();
    }
}
?>
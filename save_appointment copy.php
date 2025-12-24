<?php
header('Content-Type: application/json');

include __DIR__ . '/db.connection/db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Get POST data
$name  = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['number'] ?? '';
$reason = $_POST['meassage'] ?? '';
$date  = $_POST['date'] ?? '';
$slot  = $_POST['slot'] ?? '';

// Simple validation
if (!$name || !$email || !$phone || !$date || !$slot) {
    echo json_encode(['status'=>'error', 'message'=>'Missing required fields']);
    exit;
}

// Check slot availability in database
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE appointment_date=? AND appointment_slot=?");
$stmt->bind_param("ss", $date, $slot);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 5) {
    echo json_encode(['status'=>'full']);
    exit;
}

// Save appointment
$stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, reason, appointment_date, appointment_slot, status) VALUES (?,?,?,?,?,?,?)");
$status = 'Booked';
$stmt->bind_param("sssssss", $name, $email, $phone, $reason, $date, $slot, $status);

if ($stmt->execute()) {
    // Send email confirmation
    $mail = new PHPMailer(true);
    try {
        // Server settings
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourdomain.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@domain.com'; // SMTP username
        $mail->Password   = 'your-email-password';    // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your-email@domain.com', 'Appointment System');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Appointment Confirmation";
        $mail->Body    = "
            <h3>Dear $name,</h3>
            <p>Your appointment has been successfully booked!</p>
            <p><strong>Date:</strong> $date<br>
            <strong>Time Slot:</strong> $slot<br>
            <strong>Phone:</strong> $phone</p>
            ".($reason ? "<p><strong>Reason:</strong> $reason</p>" : "")."
            <p>To cancel your appointment, visit the booking portal and go to 'My Bookings'.</p>
            <p>Thank you!</p>
        ";

        $mail->send();

        echo json_encode(['status'=>'success']);
    } catch (Exception $e) {
        echo json_encode(['status'=>'error', 'message'=>'Mailer Error: '.$mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status'=>'error', 'message'=>$conn->error]);
}
?>

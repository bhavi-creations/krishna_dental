<?php
include __DIR__ . '/db.connection/db_connection.php';
require_once __DIR__ . '/appointment_helpers.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: appointment.php');
    exit;
}

$name  = trim($_POST['name'] ?? $_POST['patient_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date  = trim($_POST['appointment_date'] ?? '');
$slot  = trim($_POST['time_slot'] ?? $_POST['appointment_time'] ?? '');
$msg   = trim($_POST['message'] ?? $_POST['reason'] ?? '');

if ($name === '' || $phone === '' || $date === '' || $slot === '') {
    echo "<script>
        alert('Please fill all required fields.');
        window.history.back();
    </script>";
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Please enter a valid email address.');
        window.history.back();
    </script>";
    exit;
}

$day = date('l', strtotime($date));
$legacySchema = appointment_uses_legacy_schema($conn);
$allowedSlots = appointment_slot_labels();
$morningSlots = appointment_morning_slots();
$afternoonSlots = appointment_afternoon_slots();

if (!in_array($slot, $allowedSlots, true)) {
    echo "<script>
        alert('Invalid appointment slot selected.');
        window.history.back();
    </script>";
    exit;
}

/* ======================
   HOLIDAY CHECK
====================== */
$stmt = $conn->prepare(
    'SELECT holiday_type, reason
     FROM holidays
     WHERE holiday_date = ?'
);

if (!$stmt) {
    die('Holiday query failed');
}

$stmt->bind_param('s', $date);
$stmt->execute();
$holidayResult = $stmt->get_result();

if ($holidayResult && $holidayResult->num_rows > 0) {
    $holiday = $holidayResult->fetch_assoc();
    $type = $holiday['holiday_type'];

    if (
        $type === 'fullday' ||
        ($type === 'morning' && in_array($slot, $morningSlots, true)) ||
        ($type === 'afternoon' && in_array($slot, $afternoonSlots, true))
    ) {
        echo "<script>
            alert('" . addslashes($holiday['reason']) . "');
            window.history.back();
        </script>";
        exit;
    }
}

$stmt->close();

/* ======================
   SLOT LIMIT CHECK
====================== */
$slotColumn = appointment_slot_column($conn);
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM appointments
     WHERE appointment_date = ? AND {$slotColumn} = ?"
);

if (!$stmt) {
    die('Slot query failed');
}

$stmt->bind_param('ss', $date, $slot);
$stmt->execute();
$countRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ((int) ($countRow['total'] ?? 0) >= 3) {
    echo "<script>
        alert('This time slot is full. Please choose another time.');
        window.history.back();
    </script>";
    exit;
}

/* ======================
   INSERT APPOINTMENT
====================== */
if ($legacySchema) {
    $stmt = $conn->prepare(
        'INSERT INTO appointments
        (patient_name, phone, appointment_date, appointment_time)
        VALUES (?, ?, ?, ?)'
    );

    if (!$stmt) {
        die('Insert query failed');
    }

    $stmt->bind_param('ssss', $name, $phone, $date, $slot);
} else {
    $stmt = $conn->prepare(
        'INSERT INTO appointments
        (name, email, phone, appointment_date, time_slot, message)
        VALUES (?, ?, ?, ?, ?, ?)'
    );

    if (!$stmt) {
        die('Insert query failed');
    }

    $stmt->bind_param('ssssss', $name, $email, $phone, $date, $slot, $msg);
}

if (!$stmt->execute()) {
    echo "<script>
        alert('Appointment booking failed.');
        window.history.back();
    </script>";
    exit;
}

$stmt->close();

/* ======================
   MAIL TO DOCTOR
====================== */
$mailDoctor = new PHPMailer(true);

try {
    $mailDoctor->isSMTP();
    $mailDoctor->Host       = 'smtp.gmail.com';
    $mailDoctor->SMTPAuth   = true;
    $mailDoctor->Username   = 'manimalladi05@gmail.com';
    $mailDoctor->Password   = 'cvarqcchfjpawxvo';
    $mailDoctor->SMTPSecure = 'tls';
    $mailDoctor->Port       = 587;

    $mailDoctor->setFrom('manimalladi05@gmail.com', 'Clinic Appointment System');
    $mailDoctor->addAddress('manimalladi05@gmail.com');
    $mailDoctor->isHTML(true);
    $mailDoctor->Subject = 'New Appointment Booked';
    $mailDoctor->Body = "
        <h2>New Appointment Details</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Date:</strong> {$date} ({$day})</p>
        <p><strong>Time Slot:</strong> {$slot}</p>
        <p><strong>Message:</strong> {$msg}</p>
    ";

    $mailDoctor->send();
} catch (Exception $e) {
    error_log('Doctor Mail Error: ' . $mailDoctor->ErrorInfo);
}

/* ======================
   MAIL TO PATIENT
====================== */
if ($email !== '') {
    $mailPatient = new PHPMailer(true);

    try {
        $mailPatient->isSMTP();
        $mailPatient->Host       = 'smtp.gmail.com';
        $mailPatient->SMTPAuth   = true;
        $mailPatient->Username   = 'manimalladi05@gmail.com';
        $mailPatient->Password   = 'cvarqcchfjpawxvo';
        $mailPatient->SMTPSecure = 'tls';
        $mailPatient->Port       = 587;

        $mailPatient->setFrom(
            'manimalladi05@gmail.com',
            'Srinivasa Multispeciality Dental Hospital'
        );
        $mailPatient->addAddress($email);
        $mailPatient->isHTML(true);
        $mailPatient->Subject = 'Appointment Confirmation';
        $mailPatient->Body = "
            <h2>Appointment Confirmed ✅</h2>
            <p>Dear <strong>{$name}</strong>,</p>
            <p>Your appointment has been successfully booked.</p>
            <table cellpadding='6'>
                <tr><td><strong>Date</strong></td><td>{$date} ({$day})</td></tr>
                <tr><td><strong>Time</strong></td><td>{$slot}</td></tr>
                <tr><td><strong>Phone</strong></td><td>{$phone}</td></tr>
            </table>
            <p>Thank you for choosing<br>
            <b>Srinivasa Multispeciality Dental Hospital</b>.</p>
        ";

        $mailPatient->send();
    } catch (Exception $e) {
        error_log('Patient Mail Error: ' . $mailPatient->ErrorInfo);
    }
}

header('Location: thankyou.php');
exit;

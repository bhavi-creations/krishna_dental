<?php
include './db.connection/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ======================
       GET & CLEAN INPUT
       ====================== */
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date  = $_POST['appointment_date'];
    $slot  = $_POST['time_slot'];
    $msg   = trim($_POST['message']);

    $day = date('l', strtotime($date));

    /* ======================
       HOLIDAY CHECK
       ====================== */
    $stmt = $conn->prepare(
        "SELECT holiday_type, reason 
         FROM holidays 
         WHERE holiday_date=?"
    );
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $h = $stmt->get_result();

    if ($h->num_rows > 0) {
        $row  = $h->fetch_assoc();
        $type = $row['holiday_type'];

        $morningSlots = [

            "09:00 AM - 10:00 AM",
            "10:00 AM - 11:00 AM",
            "11:00 AM - 12:00 PM",
            "12:00 PM - 01:00 PM",
            "01:00 PM - 02:00 PM"
        ];

        $afternoonSlots = [
            "02:00 PM - 03:00 PM",
            "03:00 PM - 04:00 PM",
            "04:00 PM - 05:00 PM",
            "05:00 PM - 06:00 PM",
            "06:00 PM - 07:00 PM",
            "07:00 PM - 08:30 PM",
            
        ];

        if (
            $type == 'fullday' ||
            ($type == 'morning' && in_array($slot, $morningSlots)) ||
            ($type == 'afternoon' && in_array($slot, $afternoonSlots))
        ) {
            echo "<script>
                alert('".$row['reason']."');
                window.location='index.php';
            </script>";
            exit;
        }
    }

    /* ======================
       SLOT LIMIT CHECK
       ====================== */
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total 
         FROM appointments 
         WHERE appointment_date=? AND time_slot=?"
    );
    $stmt->bind_param("ss", $date, $slot);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc();

    if ($count['total'] >= 3) {
        echo "<script>
            alert('This time slot is FULL');
            window.location='index.php';
        </script>";
        exit;
    }

    /* ======================
       INSERT APPOINTMENT
       ====================== */
    $stmt = $conn->prepare(
        "INSERT INTO appointments 
        (name, email, phone, appointment_date, time_slot, message)
        VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param("ssssss", $name, $email, $phone, $date, $slot, $msg);
    $stmt->execute();

    /* ======================
       MAIL TO DOCTOR ONLY
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

        $mailDoctor->setFrom(
            'manimalladi05@gmail.com',
            'Clinic Appointment System'
        );

        $mailDoctor->addAddress('manimalladi05@gmail.com');

        $mailDoctor->isHTML(true);
        $mailDoctor->Subject = 'New Appointment Booked';

        $mailDoctor->Body = "
            <h2>New Appointment Details</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Date:</strong> $date ($day)</p>
            <p><strong>Time Slot:</strong> $slot</p>
            <p><strong>Message:</strong> $msg</p>
        ";

        $mailDoctor->send();

    } catch (Exception $e) {
        echo 'Doctor Mail Error: ' . $mailDoctor->ErrorInfo;
        exit;
    }

    /* ======================
       MAIL TO PATIENT ONLY
       ====================== */
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
            <p>Dear <strong>$name</strong>,</p>

            <p>Your appointment has been successfully booked.</p>

            <table cellpadding='6'>
                <tr><td><strong>Date</strong></td><td>$date ($day)</td></tr>
                <tr><td><strong>Time</strong></td><td>$slot</td></tr>
                <tr><td><strong>Phone</strong></td><td>$phone</td></tr>
            </table>

            <p>Thank you for choosing<br>
            <b>Srinivasa Multispeciality Dental Hospital</b>.</p>
        ";

        $mailPatient->send();

        echo "<script>
          
            window.location='thankyou.php';
        </script>";

    } catch (Exception $e) {
        echo 'Patient Mail Error: ' . $mailPatient->ErrorInfo;
    }
}
?>

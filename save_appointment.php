<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/* =====================
   1. DB CONNECTION
===================== */
$host = "localhost";
$user = "krishnadentacureclinic";
$pass = "ip4IvBVvK8TlT7y";
$db   = "krishnadentacureclinic";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

/* =====================
   2. FORM DATA
===================== */
$name  = $_POST['patient_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$date  = $_POST['appointment_date'] ?? '';
$time  = $_POST['appointment_time'] ?? '';

/* =====================
   3. SAVE TO DATABASE
===================== */
$sql = "INSERT INTO appointments 
        (patient_name, phone, appointment_date, appointment_time)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $date, $time);

if ($stmt->execute()) {

    /* =====================
       4. SEND MAIL
    ===================== */
    $mail = new PHPMailer(true);

    try {

        // 🔍 DEBUG (problem unte 2, work ayyaka 0)
        // $mail->SMTPDebug = 2;

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'manimalladi05@gmail.com'; // YOUR GMAIL
        $mail->Password   = 'qrfjvgmiozxlhcev';        // APP PASSWORD ONLY

        // ✅ IMPORTANT FIX
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // 🔥 SSL CERTIFICATE FIX (LOCALHOST)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('manimalladi05@gmail.com', 'Krishna Dental Care');
        $mail->addAddress('manimalladi05@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'New Appointment Booked';
        $mail->Body    = "
            <h3>New Appointment Details</h3>
            <p><b>Name:</b> $name</p>
            <p><b>Phone:</b> $phone</p>
            <p><b>Date:</b> $date</p>
            <p><b>Time:</b> $time</p>
        ";

        $mail->send();

        echo "<script>
                alert('Appointment booked & Mail sent successfully!');
                window.location.href='index.php';
              </script>";

    } catch (Exception $e) {
        echo "Mail Error: " . $mail->ErrorInfo;
    }

} else {
    echo "Database Insert Failed";
}

$stmt->close();
$conn->close();




?>

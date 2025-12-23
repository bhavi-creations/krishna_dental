<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ఫారం నుండి డేటాను తీసుకోవడం
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone   = strip_tags(trim($_POST["number"]));
    $message = strip_tags(trim($_POST["meassage"]));
    $date    = strip_tags($_POST["date"]);
    $slot    = strip_tags($_POST["slot"]);

    // --- ఇక్కడ మీ ఈమెయిల్ అడ్రస్ ఇవ్వండి ---
    $to = "manimalladi05@gmail.com"; 
    
    $subject = "New Appointment: $name ($date)";

    // ఈమెయిల్ కంటెంట్
    $email_content = "You have a new appointment booking details:\n\n";
    $email_content .= "Full Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Date: $date\n";
    $email_content .= "Time Slot: $slot\n";
    $email_content .= "Reason: $message\n";

    // ఈమెయిల్ హెడర్స్
    $headers = "From: $name <$email>";

    // మెయిల్ పంపడం
    if (mail($to, $subject, $email_content, $headers)) {
        // సక్సెస్ అయితే మెయిన్ పేజీకి వెళ్తుంది
        header("Location: index.php?booking=success");
    } else {
        // ఫెయిల్ అయితే ఎర్రర్ చూపిస్తుంది
        header("Location: index.php?booking=error");
    }
} else {
    echo "Direct access not allowed.";
}
?>
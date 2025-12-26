<?php
include './db.connection/db_connection.php';

$msg = '';

if (isset($_POST['add_holiday'])) {

    $date   = $_POST['holiday_date'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare(
        "INSERT IGNORE INTO holidays (holiday_date, reason) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $date, $reason);

    if ($stmt->execute()) {
        $msg = "Holiday added successfully";
    } else {
        $msg = "Failed to add holiday";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Holiday</title>
    <style>
        body { background:#f4f6f9; font-family: Arial; }
        .box {
            width:400px; margin:100px auto;
            background:#fff; padding:25px;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,.1);
        }
        input, textarea, button {
            width:100%; padding:10px; margin-top:10px;
        }
        button {
            background:#dc3545; color:#fff;
            border:none; border-radius:5px;
        }
        .msg { color:green; margin-top:10px; }
    </style>
</head>
<body>

<div class="box">
    <h3>Add Holiday (Manual)</h3>

    <?php if($msg): ?>
        <div class="msg"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Date</label>
        <input type="date" name="holiday_date" required>

        <label>Reason</label>
        <textarea name="reason" placeholder="Optional"></textarea>

        <button type="submit" name="add_holiday">Mark as Holiday</button>
    </form>
</div>

</body>
</html>

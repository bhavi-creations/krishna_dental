<?php
include __DIR__ . '/db.connection/db_connection.php';

/* ---- Get ID ---- */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid Appointment ID");
}

/* ---- Update Logic ---- */
if (isset($_POST['update'])) {

    $name  = $_POST['patient_name'];
    $phone = $_POST['phone'];
    $date  = $_POST['appointment_date'];
    $time  = $_POST['appointment_time'];

    $stmt = $conn->prepare("
        UPDATE appointments 
        SET patient_name = ?, phone = ?, appointment_date = ?, appointment_time = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssssi", $name, $phone, $date, $time, $id);

    if ($stmt->execute()) {
        header("Location: view_appointments.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Update Failed</div>";
    }
}

/* ---- Fetch Existing Data ---- */
$result = $conn->query("SELECT * FROM appointments WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Appointment Not Found");
}
?>
<?php include 'header.php';?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <!-- Bootstrap 5 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .edit-container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; color: #0d6efd; }
        .form-label { font-weight: 500; }
        .btn-group { display: flex; justify-content: space-between; }
    </style>
</head>
<body>

<div class="container edit-container">
    <h2>Edit Appointment</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" value="<?= htmlspecialchars($data['patient_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Appointment Date</label>
            <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($data['appointment_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Appointment Time</label>
            <input type="text" name="appointment_time" class="form-control" value="<?= htmlspecialchars($data['appointment_time']); ?>" required>
        </div>

        <div class="btn-group mt-4">
            <button type="submit" name="update" class="btn btn-primary mx-1">Update Appointment</button>
            <a href="view_appointments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>

<?php include 'footer.php'; ?>
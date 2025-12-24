<?php
include __DIR__ . '/db.connection/db_connection.php';

/* ---- Get ID ---- */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid Appointment ID");
}

/* ---- Update Logic ---- */
if (isset($_POST['update'])) {

    $name   = $_POST['name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $reason = $_POST['reason'];
    $date   = $_POST['appointment_date'];
    $slot   = $_POST['appointment_slot'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE appointments 
        SET name = ?, email = ?, phone = ?, reason = ?, appointment_date = ?, appointment_slot = ?, status = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssssi", $name, $email, $phone, $reason, $date, $slot, $status, $id);

    if ($stmt->execute()) {
        header("Location: view_appointments.php");
        exit;
    } else {
        echo "Update Failed: " . $conn->error;
    }
}

/* ---- Fetch Existing Data ---- */
$result = $conn->query("SELECT * FROM appointments WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Appointment Not Found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#f4f6f9;
        }
        .card{
            border-radius:12px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);
        }
        .card-header{
            background:#0d6efd;
            color:#fff;
            font-weight:600;
            font-size:1.2rem;
            border-radius:12px 12px 0 0;
        }
        .btn-primary{
            background:#0d6efd;
            border:none;
        }
        .btn-secondary{
            border-radius:6px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card">
                <div class="card-header">✏️ Edit Appointment</div>
                <div class="card-body p-4">

                    <form method="POST" class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['name']); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Appointment Date</label>
                            <input type="date" name="appointment_date" value="<?= $data['appointment_date']; ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Appointment Slot</label>
                            <input type="text" name="appointment_slot" value="<?= htmlspecialchars($data['appointment_slot']); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Booked" <?= ($data['status']=='Booked')?'selected':''; ?>>Booked</option>
                                <option value="Cancelled" <?= ($data['status']=='Cancelled')?'selected':''; ?>>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Reason</label>
                            <textarea name="reason" class="form-control" rows="3"><?= htmlspecialchars($data['reason']); ?></textarea>
                        </div>

                        <div class="col-12 mt-3 d-flex justify-content-end">
                            <a href="view_appointments.php" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" name="update" class="btn btn-primary">Update Appointment</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>

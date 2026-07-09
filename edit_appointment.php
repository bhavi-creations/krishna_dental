<?php
include __DIR__ . '/db.connection/db_connection.php';
require_once __DIR__ . '/appointment_helpers.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Invalid Appointment ID');
}

$legacySchema = appointment_uses_legacy_schema($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    if ($legacySchema) {
        $name  = trim($_POST['patient_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date  = trim($_POST['appointment_date'] ?? '');
        $time  = trim($_POST['appointment_time'] ?? '');

        $stmt = $conn->prepare(
            'UPDATE appointments
             SET patient_name = ?, phone = ?, appointment_date = ?, appointment_time = ?
             WHERE id = ?'
        );

        if ($stmt) {
            $stmt->bind_param('ssssi', $name, $phone, $date, $time, $id);
            if ($stmt->execute()) {
                header('Location: view_appointments.php');
                exit;
            }
        }
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $date    = trim($_POST['appointment_date'] ?? '');
        $time    = trim($_POST['time_slot'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $stmt = $conn->prepare(
            'UPDATE appointments
             SET name = ?, email = ?, phone = ?, appointment_date = ?, time_slot = ?, message = ?
             WHERE id = ?'
        );

        if ($stmt) {
            $stmt->bind_param('ssssssi', $name, $email, $phone, $date, $time, $message, $id);
            if ($stmt->execute()) {
                header('Location: view_appointments.php');
                exit;
            }
        }
    }

    $update_error = 'Update Failed';
}

$sql = 'SELECT ' . appointment_select_sql($conn) . ' FROM appointments WHERE id = ?';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Appointment not found');
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result ? $result->fetch_assoc() : null;

if (!$data) {
    die('Appointment Not Found');
}
?>
<?php include 'header.php'; ?>

<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .edit-container {
        max-width: 720px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
</style>

<div class="container edit-container">
    <h2 class="text-center mb-4">Edit Appointment</h2>

    <?php if (!empty($update_error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($update_error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <?php if ($legacySchema) : ?>
            <div class="mb-3">
                <label class="form-label">Patient Name</label>
                <input type="text" name="patient_name" class="form-control" value="<?= htmlspecialchars($data['patient_name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($data['appointment_date'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Time</label>
                <input type="text" name="appointment_time" class="form-control" value="<?= htmlspecialchars($data['appointment_time'] ?? ''); ?>" required>
            </div>
        <?php else : ?>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['patient_name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($data['appointment_date'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Time</label>
                <input type="text" name="time_slot" class="form-control" value="<?= htmlspecialchars($data['appointment_time'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="4"><?= htmlspecialchars($data['message'] ?? ''); ?></textarea>
            </div>
        <?php endif; ?>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" name="update" class="btn btn-primary">Update Appointment</button>
            <a href="view_appointments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>

<?php
include __DIR__ . '/db.connection/db_connection.php';
require_once __DIR__ . '/appointment_helpers.php';

function bind_stmt_params(mysqli_stmt $stmt, string $types, array &$params): void
{
    if ($types === '') {
        return;
    }

    $bind = [$types];
    foreach ($params as $index => $value) {
        $bind[$index + 1] = &$params[$index];
    }

    call_user_func_array([$stmt, 'bind_param'], $bind);
}

$from_date = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
$to_date   = isset($_GET['to_date']) ? trim($_GET['to_date']) : '';
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';

$nameColumn = appointment_name_column($conn);
$slotColumn = appointment_slot_column($conn);
$searchColumns = [$nameColumn, 'phone'];
if (appointment_has_column($conn, 'email')) {
    $searchColumns[] = 'email';
}

$where = [];
$params = [];
$types = '';

if ($from_date !== '' && $to_date !== '') {
    $where[] = 'appointment_date BETWEEN ? AND ?';
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= 'ss';
}

if ($search !== '') {
    $like = '%' . $search . '%';
    $searchParts = [];

    foreach ($searchColumns as $column) {
        $searchParts[] = $column . ' LIKE ?';
        $params[] = $like;
        $types .= 's';
    }

    $where[] = '(' . implode(' OR ', $searchParts) . ')';
}

$selectSql = appointment_select_sql($conn);
$sql = 'SELECT ' . $selectSql . ' FROM appointments WHERE 1';

if ($where) {
    $sql .= ' AND ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY appointment_date DESC, ' . $slotColumn . ' DESC, id DESC';

$stmt = $conn->prepare($sql);

if ($stmt) {
    bind_stmt_params($stmt, $types, $params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
}

$total_appointments = null;
if ($from_date !== '' && $to_date !== '') {
    $countStmt = $conn->prepare('SELECT COUNT(*) AS total FROM appointments WHERE appointment_date BETWEEN ? AND ?');
    if ($countStmt) {
        $countStmt->bind_param('ss', $from_date, $to_date);
        $countStmt->execute();
        $countData = $countStmt->get_result()->fetch_assoc();
        $total_appointments = (int) ($countData['total'] ?? 0);
        $countStmt->close();
    }
}
?>
<?php include 'header.php'; ?>

<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }

    .filter-box,
    .table-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .filter-box {
        margin-bottom: 30px;
    }

    table thead th {
        background: #0d6efd;
        color: #fff;
        white-space: nowrap;
    }

    table tbody tr:hover {
        background: #f8f9fa;
    }

    .modal-content {
        border-radius: 12px;
    }
</style>

<div class="container py-5">
    <h2 class="text-center mb-4">Booked Appointments</h2>

    <div class="filter-box">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($from_date); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($to_date); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Name / Phone / Email</label>
                <input type="text" name="search" class="form-control" placeholder="Enter name, phone or email" value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="view_appointments.php" class="btn btn-outline-secondary mt-2">Reset</a>
            </div>
        </form>

        <?php if ($total_appointments !== null) : ?>
            <p class="mt-3 mb-0"><strong>Total Appointments:</strong> <?= $total_appointments; ?></p>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>S. No</th>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0) : ?>
                        <?php $sn = 1; ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $sn++; ?></td>
                                <td><?= htmlspecialchars($row['patient_name'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['email'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['phone'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['appointment_date'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['appointment_time'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($row['message'] ?? ''); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info text-white mb-1" onclick="openModal(<?= (int) $row['id']; ?>)">
                                        View
                                    </button>
                                    <a href="edit_appointment.php?id=<?= (int) $row['id']; ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
                                    <a href="delete_appointment.php?id=<?= (int) $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this appointment?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">No Appointments Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="detailModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        fetch('get_appointment_details.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                const html = `
                    <p><strong>Patient Name:</strong> ${data.patient_name ?? ''}</p>
                    <p><strong>Email:</strong> ${data.email ?? ''}</p>
                    <p><strong>Phone:</strong> ${data.phone ?? ''}</p>
                    <p><strong>Date:</strong> ${data.appointment_date ?? ''}</p>
                    <p><strong>Time:</strong> ${data.appointment_time ?? ''}</p>
                    <p><strong>Message:</strong> ${data.message ?? ''}</p>
                `;
                document.getElementById('modalBody').innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                modal.show();
            });
    }

    function closeModal() {
        const modalEl = document.getElementById('detailModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    }
</script>

<?php include 'footer.php'; ?>

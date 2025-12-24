<?php
include __DIR__ . '/db.connection/db_connection.php';

/* ---- Filters ---- */
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date   = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM appointments WHERE 1";

if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND appointment_date BETWEEN '$from_date' AND '$to_date'";
}

if (!empty($search)) {
    $sql .= " AND (patient_name LIKE '%$search%' OR phone LIKE '%$search%')";
}

$sql .= " ORDER BY appointment_date DESC, appointment_time ASC";

$result = $conn->query($sql);
?>
<?php include 'header.php'; ?>
<?php
include __DIR__ . '/db.connection/db_connection.php';

/* ---- Filters ---- */
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date   = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM appointments WHERE 1";

if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND appointment_date BETWEEN '$from_date' AND '$to_date'";
}

if (!empty($search)) {
    $sql .= " AND (patient_name LIKE '%$search%' OR phone LIKE '%$search%')";
}

$sql .= " ORDER BY appointment_date DESC, appointment_time ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Appointments List</title>
    <!-- Bootstrap 5 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .filter-box {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        table th {
            background-color: #0d6efd;
            color: #0d6efd;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            font-size: 14px;
            padding: 5px 10px;
        }

        .eye-icon {
            font-size: 18px;
            cursor: pointer;
            color: #0d6efd;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
        }

        .close {
            font-size: 28px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <h2 class="text-center">Booked Appointments</h2>

        <!-- Filter Section -->
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
                    <label class="form-label">Name / Phone</label>
                    <input type="text" name="search" class="form-control" placeholder="Enter Name or Phone" value="<?= htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="view_appointments.php" class="btn btn-outline-secondary mt-2">Reset</a>
                </div>
            </form>

            <?php
            // Show total appointments for selected date range
            if (!empty($from_date) && !empty($to_date)) {
                $count_sql = "SELECT COUNT(*) as total FROM appointments WHERE appointment_date BETWEEN '$from_date' AND '$to_date'";
                $count_result = $conn->query($count_sql);
                $count_data = $count_result->fetch_assoc();
                $total_appointments = $count_data['total'];
                echo "<p class='mt-3'><strong>Total Appointments:</strong> $total_appointments</p>";
            }
            ?>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>S. No</th>
                            <th>Patient Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) {
                            $sn = 1; // Serial number starts from 1
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?= htmlspecialchars($row['phone']); ?></td>
                                    <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                                    <td><?= htmlspecialchars($row['appointment_time']); ?></td>
                                    <td>
                                        <span class="eye-icon me-2" onclick="openModal(<?= $row['id']; ?>)">👁️</span>
                                        <a href="delete_appointment.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this appointment?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" class="text-center">No Appointments Found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
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

    <!-- Bootstrap 5 JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script>
        function openModal(id) {
            fetch('get_appointment_details.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    let html = `
                    <p><strong>Patient Name:</strong> ${data.patient_name}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    <p><strong>Date:</strong> ${data.appointment_date}</p>
                    <p><strong>Time:</strong> ${data.appointment_time}</p>
                `;
                    document.getElementById('modalBody').innerHTML = html;
                    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
                    myModal.show();
                });
        }

        function closeModal() {
            var myModalEl = document.getElementById('detailModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        }
    </script>

</body>

</html>

<?php include 'footer.php'; ?>
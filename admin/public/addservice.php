<?php
include '../../db.connection/db_connection.php'; // DB connection

$edit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service_name = '';
$service_id = '';

// Fetch data for editing
if ($edit_id > 0) {
    $stmt = $conn->prepare("SELECT service_id, service_name FROM services WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $stmt->bind_result($service_id, $service_name);
    $stmt->fetch();
    $stmt->close();
}

// Fetch all services
$all_services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $edit_id > 0 ? 'Update Service' : 'Add Service' ?> - Admin Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <?php include 'navbar.php'; ?>
            <!-- End of Topbar -->

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800"><?= $edit_id > 0 ? 'Update Service' : 'Add Service' ?></h1>

                <!-- Success Message -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_GET['success'] == 'added' ? 'Service added successfully!' : 'Service updated successfully!' ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Service Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success"><?= $edit_id > 0 ? 'Update Service' : 'Add Service' ?></h6>
                    </div>
                    <div class="card-body">
                        <form action="save_service.php" method="post">
                            <input type="hidden" name="id" value="<?= $edit_id ?>">
                            <div class="mb-3">
                                <label class="form-label">Service Name</label>
                                <input type="text" name="service_name" class="form-control" placeholder="Enter Service Name"
                                       value="<?= htmlspecialchars($service_name) ?>" required>
                            </div>
                            <button type="reset" class="btn btn-danger">Clear</button>
                            <button type="submit" class="btn btn-success"><?= $edit_id > 0 ? 'Update' : 'Add' ?></button>
                        </form>
                    </div>
                </div>

                <!-- All Services Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Services</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Service ID</th>
                                    <th>Service Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($all_services && $all_services->num_rows > 0): ?>
                                    <?php while($row = $all_services->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['service_id'] ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= $row['created_at'] ?></td>
                                            <td>
                                                <a href="addservice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No services added yet.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto text-center">
                <span>© 2024 VisionDentalhospital. All Rights Reserved. Designed & Developed by <a href="https://bhavicreations.com/" target="_blank">Bhavi Creations</a></span>
            </div>
        </footer>

    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>
